@props([
    'name',
    'label',
    'accept' => null,
])

<label class="form-field">
    <span class="form-label">{{ $label }}</span>
    <input
        {{ $attributes->class('file-input w-full rounded-[20px] border border-slate-200 bg-white text-base shadow-[0_10px_24px_rgba(148,163,184,0.08)]') }}
        type="file"
        name="{{ $name }}"
        @if($accept) accept="{{ $accept }}" @endif
    >
    <x-forms.error :name="$name" />
</label>
