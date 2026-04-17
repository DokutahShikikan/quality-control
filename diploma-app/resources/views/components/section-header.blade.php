@props(['title', 'description' => null])

<div {{ $attributes->class('flex items-start justify-between gap-4') }}>
    <div>
        <h2 class="panel-title">{{ $title }}</h2>
        @if($description)
            <p class="mt-3 text-base leading-7 text-slate-600 md:text-lg">{{ $description }}</p>
        @endif
    </div>

    @if(trim($slot) !== '')
        <div class="shrink-0">
            {{ $slot }}
        </div>
    @endif
</div>
