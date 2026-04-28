@props(['current' => 'issues', 'datasetId' => null])

<div class="panel conflicts-panel">
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div class="space-y-2">
            <p class="text-sm font-bold uppercase tracking-[0.16em] text-slate-500">Конфликты</p>
            <h2 class="panel-title text-2xl md:text-[2rem]">
                {{ $current === 'duplicates' ? 'Повторы в таблицах' : 'Ошибки в таблицах' }}
            </h2>
            <p class="text-sm leading-7 text-slate-600 md:text-base">
                Переключайтесь между ошибками и повторами внутри одного раздела.
            </p>
        </div>

        <div class="conflicts-tabs" role="tablist" aria-label="Раздел конфликтов">
            <a
                href="/issues{{ $datasetId ? '?dataset='.$datasetId : '' }}"
                class="conflicts-tab {{ $current === 'issues' ? 'is-active' : '' }}"
                aria-current="{{ $current === 'issues' ? 'page' : 'false' }}"
            >
                Ошибки
            </a>
            <a
                href="/duplicates{{ $datasetId ? '?dataset='.$datasetId : '' }}"
                class="conflicts-tab {{ $current === 'duplicates' ? 'is-active' : '' }}"
                aria-current="{{ $current === 'duplicates' ? 'page' : 'false' }}"
            >
                Повторы
            </a>
        </div>
    </div>
</div>
