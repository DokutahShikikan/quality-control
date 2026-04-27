@props(['sticky' => false])

<div {{ $attributes->class(['data-table-wrap overflow-x-auto', 'is-sticky-head' => $sticky]) }}>
    <table class="data-table">
        {{ $slot }}
    </table>
</div>
