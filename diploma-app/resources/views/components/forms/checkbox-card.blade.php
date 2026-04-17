@props([
    'name',
    'label',
    'checked' => false,
    'value' => 1,
])

<label {{ $attributes->class('flex items-center gap-3 rounded-[22px] border border-slate-200 bg-slate-50/90 px-4 py-4 text-base text-slate-700') }}>
    <input
        class="checkbox checkbox-primary"
        type="checkbox"
        name="{{ $name }}"
        value="{{ $value }}"
        @checked(old($name, $checked))
    >
    <span>{{ $label }}</span>
</label>
