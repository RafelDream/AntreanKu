<div>
    <div class="consent">
        <input type="checkbox" id="setuju" name="setuju" value="1" @checked(old('setuju'))
               @error('setuju') aria-invalid="true" aria-describedby="setuju-error" @enderror>
        <label for="setuju">Saya setuju dengan Syarat dan Ketentuan serta Kebijakan Privasi.</label>
    </div>
    @error('setuju')
        <p id="setuju-error" class="field__error" role="alert">{{ $message }}</p>
    @enderror
</div>
