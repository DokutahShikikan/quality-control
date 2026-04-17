@props(['label', 'value'])

<div {{ $attributes->class('metric-card') }}>
    <div class="metric-label">{{ $label }}</div>
    <div class="metric-value">{{ $value }}</div>
</div>
