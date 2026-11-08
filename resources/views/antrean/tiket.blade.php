@extends('layouts.app')

@section('title', 'Nomor Antrean — ANTREANKU')

@section('content')
    <header class="page-header">
        <h1 class="page-title">Antrean</h1>
    </header>

    @if (session('berhasil'))
        <div class="alert alert--ok" role="status" data-auto-hide>{{ session('berhasil') }}</div>
    @endif
    @if (session('info'))
        <div class="alert alert--warn" role="status" data-auto-hide>{{ session('info') }}</div>
    @endif

    {{-- Alamat relatif (tanpa http/https) supaya aman saat diakses lewat ngrok --}}
    <div id="tiket"
         data-status-url="{{ route('antrean.status', $antrean->token, false) }}"
         data-batal-url="/api/antrean/batal"
         data-token="{{ $antrean->token }}">

        <section class="ticket" aria-label="Nomor antrean Anda">
            <p class="ticket__label">Nomor antrean Anda</p>
            <p class="ticket__number">{{ $antrean->nomor_antrean }}</p>
            <p class="ticket__name">{{ $antrean->pasien->nama_lengkap }}</p>

            <dl class="ticket__meta">
                <div>
                    <dt>No. Rekam Medis</dt>
                    <dd>{{ $antrean->pasien->no_rekam_medis }}</dd>
                </div>
                <div>
                    <dt>Tanggal</dt>
                    <dd>{{ $antrean->tanggal_antrean->translatedFormat('d F Y') }}</dd>
                </div>
            </dl>
        </section>

        <div class="status-row" aria-live="polite">
            <span id="status-chip" class="chip chip--{{ $ringkasan['status'] }}">{{ $ringkasan['status_label'] }}</span>
        </div>

        <div class="stats">
            <div class="stat">
                <span>Sedang dilayani antrian</span>
                <strong id="sedang">{{ $ringkasan['sedang_dilayani'] ?? '—' }}</strong>
            </div>
            <div class="stat">
                <span>Antrean di depan Anda</span>
                <strong id="sisa">{{ $ringkasan['sisa_di_depan'] ?? '—' }}</strong>
            </div>
        </div>

        <p id="koneksi" class="alert alert--warn" role="status" hidden>Koneksi Terputus. Data antrean belum diperbarui.</p>

        {{-- Batalkan antrean: hanya tampil selama status "menunggu" (disembunyikan otomatis oleh polling) --}}
        <p id="batal-error" class="alert alert--error" role="alert" hidden></p>
        <div id="batal-info" class="toast" role="status" aria-live="polite" hidden></div>

        <div id="aksi-batal" class="aksi" @if ($ringkasan['status'] !== 'menunggu') hidden @endif>
            <button type="button" id="open-modal-batal" class="btn btn--danger-outline">Batalkan Antrean</button>
        </div>

        {{-- Setelah dibatalkan: kembali ke halaman utama --}}
        <div id="aksi-kembali" class="aksi" @if ($ringkasan['status'] !== 'dibatalkan') hidden @endif>
            <a href="{{ route('beranda') }}" class="btn btn--primary">Kembali ke Halaman Utama</a>
        </div>
    </div>

    {{-- Popup konfirmasi pembatalan --}}
    <div id="modal-batal" class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-batal-judul" hidden>
        <div class="modal__box">
            <h2 id="modal-batal-judul" class="modal__title">Konfirmasi Pembatalan</h2>
            <p class="modal__text">Apakah Anda yakin ingin membatalkan nomor antrean ini? Tindakan ini tidak dapat dibatalkan.</p>

            <button type="button" id="btn-eksekusi-batal" class="btn btn--danger">Ya, Batalkan</button>
            <button type="button" id="btn-tutup-modal" class="btn btn--ghost">Tidak</button>
        </div>
    </div>

    <p class="switch">Nomor ini hanya berlaku hari ini.</p>
@endsection

@push('scripts')
<script>
(function () {
    var box = document.getElementById('tiket');
    var koneksi = document.getElementById('koneksi');
    var url = box.getAttribute('data-status-url');
    var batalUrl = box.getAttribute('data-batal-url');
    var token = box.getAttribute('data-token');

    var aksiBatal = document.getElementById('aksi-batal');
    var aksiKembali = document.getElementById('aksi-kembali');
    var batalInfo = document.getElementById('batal-info');
    var batalError = document.getElementById('batal-error');
    var modal = document.getElementById('modal-batal');
    var btnBuka = document.getElementById('open-modal-batal');
    var btnTutup = document.getElementById('btn-tutup-modal');
    var btnBatal = document.getElementById('btn-eksekusi-batal');

    /* ---------- Pesan di atas halaman hilang otomatis (3 detik) ---------- */

    Array.prototype.forEach.call(document.querySelectorAll('[data-auto-hide]'), function (el) {
        setTimeout(function () {
            el.style.transition = 'opacity .3s';
            el.style.opacity = '0';
            setTimeout(function () { el.hidden = true; }, 300);
        }, 3000);
    });

    /* ---------- Polling status ---------- */

    function tampil(d) {
        document.getElementById('sedang').textContent = d.sedang_dilayani != null ? d.sedang_dilayani : '—';
        document.getElementById('sisa').textContent = d.sisa_di_depan != null ? d.sisa_di_depan : '—';
        var chip = document.getElementById('status-chip');
        chip.textContent = d.status_label;
        chip.className = 'chip chip--' + d.status;

        aksiBatal.hidden = (d.status !== 'menunggu');
        aksiKembali.hidden = (d.status !== 'dibatalkan');
    }

    function perbarui() {
        if (document.hidden) { return; }
        fetch(url, { headers: { 'Accept': 'application/json' }, cache: 'no-store' })
            .then(function (r) { if (!r.ok) { throw new Error(r.status); } return r.json(); })
            .then(function (d) { koneksi.hidden = true; tampil(d); })
            .catch(function () { koneksi.hidden = false; });
    }

    /* ---------- Popup batalkan antrean ---------- */

    function bukaModal() {
        batalError.hidden = true;
        modal.hidden = false;
        btnTutup.focus(); // fokus awal ke "Tidak" agar tidak terpencet tak sengaja
    }

    function tutupModal() {
        modal.hidden = true;
        if (!aksiBatal.hidden) { btnBuka.focus(); }
    }

    function tampilError(pesan) {
        batalError.textContent = pesan;
        batalError.hidden = false;
    }

    // Notifikasi singkat: tampil 3 detik, memudar, lalu hilang sendiri (tanpa refresh)
    var toastTimer = null;
    function tampilToast(pesan) {
        clearTimeout(toastTimer);
        batalInfo.textContent = pesan;
        batalInfo.classList.remove('toast--out');
        batalInfo.hidden = false;
        toastTimer = setTimeout(function () {
            batalInfo.classList.add('toast--out');
            setTimeout(function () { batalInfo.hidden = true; }, 300);
        }, 3000);
    }

    btnBuka.addEventListener('click', bukaModal);
    btnTutup.addEventListener('click', tutupModal);
    modal.addEventListener('click', function (e) { if (e.target === modal) { tutupModal(); } });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !modal.hidden) { tutupModal(); }
    });

    btnBatal.addEventListener('click', function () {
        btnBatal.disabled = true;
        btnBatal.textContent = 'Memproses...';

        fetch(batalUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ token: token })
        })
        .then(function (r) { return r.json(); })
        .then(function (res) {
            tutupModal();
            if (res.success) {
                tampilToast(res.message || 'Antrean berhasil dibatalkan.');
                perbarui();
            } else {
                tampilError(res.message || 'Gagal membatalkan antrean.');
            }
        })
        .catch(function () {
            tutupModal();
            tampilError('Terjadi kesalahan koneksi. Coba lagi.');
        })
        .then(function () {
            btnBatal.disabled = false;
            btnBatal.textContent = 'Ya, Batalkan';
        });
    });

    setInterval(perbarui, 5000);
    document.addEventListener('visibilitychange', perbarui);
    window.addEventListener('online', perbarui);
    window.addEventListener('offline', function () { koneksi.hidden = false; });
})();
</script>
@endpush