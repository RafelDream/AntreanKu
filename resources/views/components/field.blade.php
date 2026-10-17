@props(['name', 'label', 'type' => 'text'])

@php($error = $errors->first($name))

<div class="field">
    <label for="{{ $name }}" class="field__label">{{ $label }}</label>
    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name) }}"
        @class(['field__input', 'is-invalid' => $error !== ''])
        @if ($error !== '') aria-invalid="true" aria-describedby="{{ $name }}-error" @endif
        {{ $attributes }}
    >
    @if ($error !== '')
        <p id="{{ $name }}-error" class="field__error" role="alert">{{ $error }}</p>
    @endif
</div>
