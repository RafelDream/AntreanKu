@extends('layouts.app')

@section('title', 'Nomor Antrean — ANTREANKU')

@section('content')
    <header class="page-header">
        <h1 class="page-title">Antrean</h1>
    </header>

    @if (session('berhasil'))
        <div class="alert alert--ok" role="status">{{ session('berhasil') }}</div>
    @endif
    @if (session('info'))
        <div class="alert alert--warn" role="status">{{ session('info') }}</div>
    @endif

    <div id="tiket" 
         data-status-url="{{ route('antrean.status', $antrean->token) }}"
         data-batal-url="{{ url('/api/antrean/batal') }}"
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
                <span>Sedang dilayani</span>
                <strong id="sedang">{{ $ringkasan['sedang_dilayani'] ?? '—' }}</strong>
            </div>
            <div class="stat">
                <span>Antrean di depan Anda</span>
                <strong id="sisa">{{ $ringkasan['sisa_di_depan'] ?? '—' }}</strong>
            </div>
        </div>

        {{-- Tombol Batalkan Antrean --}}
        <div id="aksi-batal" style="margin-top: 1.5rem; text-align: center;" @if($ringkasan['status'] !== 'menunggu') hidden @endif>
            <button type="button" id="open-modal-batal" class="btn btn--danger" style="width: 100%; padding: 0.75rem; background: #dc2626; color: #fff; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer;">
                Batalkan Antrean
            </button>
        </div>

        <p id="koneksi" class="alert alert--warn" role="status" hidden>Koneksi Terputus. Data antrean belum diperbarui.</p>
    </div>

    {{-- CUSTOM POP-UP MODAL KONFIRMASI --}}
    <div id="modal-batal" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.5); z-index: 999; align-items: center; justify-content: center; padding: 1rem;">
        <div style="background: #fff; width: 100%; max-width: 400px; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
            <h3 style="font-size: 1.25rem; font-weight: bold; margin-bottom: 0.5rem; color: #1f2937;">Konfirmasi Pembatalan</h3>
            <p style="color: #4b5563; font-size: 0.95rem; margin-bottom: 1.5rem;">Apakah Anda yakin ingin membatalkan nomor antrean ini? Tindakan ini tidak dapat dibatalkan.</p>
            
            {{-- Bagian tombol saja yang dibuat sejajar di tengah (justify-content: center) --}}
            <div style="display: flex; gap: 0.75rem; justify-content: center;">
                <button type="button" id="btn-tutup-modal" style="padding: 0.6rem 1.25rem; background: #e5e7eb; color: #374151; border: none; border-radius: 0.375rem; font-weight: 500; cursor: pointer; min-width: 100px;">
                    Tidak
                </button>
                <button type="button" id="btn-eksekusi-batal" style="padding: 0.6rem 1.25rem; background: #dc2626; color: #fff; border: none; border-radius: 0.375rem; font-weight: 500; cursor: pointer; min-width: 120px;">
                    Ya, Batalkan
                </button>
            </div>
        </div>
    </div>
    {{-- END POP-UP MODAL --}}

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
    
    // Elemen Modal
    var modalBatal = document.getElementById('modal-batal');
    var openModalBtn = document.getElementById('open-modal-batal');
    var tutupModalBtn = document.getElementById('btn-tutup-modal');
    var eksekusiBatalBtn = document.getElementById('btn-eksekusi-batal');

    function tampil(d) {
        document.getElementById('sedang').textContent = d.sedang_dilayani != null ? d.sedang_dilayani : '—';
        document.getElementById('sisa').textContent = d.sisa_di_depan != null ? d.sisa_di_depan : '—';
        var chip = document.getElementById('status-chip');
        chip.textContent = d.status_label;
        chip.className = 'chip chip--' + d.status;

        if (aksiBatal) {
            aksiBatal.hidden = (d.status !== 'menunggu');
        }
    }

    function perbarui() {
        if (document.hidden) { return; }
        fetch(url, { headers: { 'Accept': 'application/json' }, cache: 'no-store' })
            .then(function (r) { if (!r.ok) { throw new Error(r.status); } return r.json(); })
            .then(function (d) { koneksi.hidden = true; tampil(d); })
            .catch(function () { koneksi.hidden = false; });
    }

    if (openModalBtn) {
        openModalBtn.addEventListener('click', function () {
            modalBatal.style.display = 'flex';
        });
    }

    if (tutupModalBtn) {
        tutupModalBtn.addEventListener('click', function () {
            modalBatal.style.display = 'none';
        });
    }

    if (eksekusiBatalBtn) {
        eksekusiBatalBtn.addEventListener('click', function () {
            eksekusiBatalBtn.disabled = true;
            eksekusiBatalBtn.textContent = 'Memproses...';

            fetch(batalUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ token: token })
            })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                modalBatal.style.display = 'none';
                eksekusiBatalBtn.disabled = false;
                eksekusiBatalBtn.textContent = 'Ya, Batalkan';

                if (res.success) {
                    perbarui();
                } else {
                    alert(res.message || 'Gagal membatalkan antrean.');
                }
            })
            .catch(function () {
                modalBatal.style.display = 'none';
                eksekusiBatalBtn.disabled = false;
                eksekusiBatalBtn.textContent = 'Ya, Batalkan';
                alert('Terjadi kesalahan koneksi.');
            });
        });
    }

    setInterval(perbarui, 5000);
    document.addEventListener('visibilitychange', perbarui);
    window.addEventListener('online', perbarui);
    window.addEventListener('offline', function () { koneksi.hidden = false; });
})();
</script>
@endpush