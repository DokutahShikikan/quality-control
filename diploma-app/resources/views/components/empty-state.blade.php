@props(['title', 'description'])

<div {{ $attributes->class('panel') }}>
    <x-section-header :title="$title" :description="$description" />
    @if(trim($slot) !== '')
        <div class="mt-8">
            {{ $slot }}
        </div>
    @endif
</div>
