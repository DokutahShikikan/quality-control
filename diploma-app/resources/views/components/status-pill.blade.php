@props(['tone' => 'review'])

@php
    $toneClass = match ($tone) {
        'clean' => 'status-clean',
        default => 'status-review',
    };
@endphp

<span {{ $attributes->class(['status-pill', $toneClass]) }}>
    {{ $slot }}
</span>
