@props(['label', 'value'])

<div {{ $attributes->class('mini-stat') }}>
    <span>{{ $label }}</span>
    <strong>{{ $value }}</strong>
</div>
