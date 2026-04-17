@props([
    'name',
    'label',
    'type' => 'text',
    'value' => null,
    'placeholder' => '',
])

<label class="form-field">
    <span class="form-label">{{ $label }}</span>
    <input
        {{ $attributes->class('text-field') }}
        type="{{ $type }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
    >
    <x-forms.error :name="$name" />
</label>
