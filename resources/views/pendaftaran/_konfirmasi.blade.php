{{-- Popup konfirmasi identitas. Wajib kirim $tutupUrl (halaman asal, untuk "Bukan saya"). --}}
@if (session('konfirmasi'))
    @php($k = session('konfirmasi'))
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-title"
         data-close-url="{{ $tutupUrl }}">
        <div class="modal__box">
            <h2 id="modal-title" class="modal__title">Apakah ini data Anda?</h2>

            <dl class="modal__data">
                @if (! empty($k['nik_tersamar']))
                    <div><dt>NIK</dt><dd>{{ $k['nik_tersamar'] }}</dd></div>
                @endif
                <div><dt>Nama</dt><dd>{{ $k['nama_lengkap'] }}</dd></div>
            </dl>

            <form method="POST" action="{{ route('masuk.konfirmasi') }}" data-once>
                @csrf
                <button type="submit" class="btn btn--primary" autofocus>Ya, ambil nomor antrean</button>
            </form>
            <a href="{{ $tutupUrl }}" class="btn btn--ghost">Bukan saya</a>
        </div>
    </div>
@endif
