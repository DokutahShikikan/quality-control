@props([
    'name',
    'label',
    'value' => null,
    'rows' => 5,
    'placeholder' => '',
])

<label class="form-field">
    <span class="form-label">{{ $label }}</span>
    <textarea
        {{ $attributes->class('text-area') }}
        name="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
    >{{ old($name, $value) }}</textarea>
    <x-forms.error :name="$name" />
</label>
