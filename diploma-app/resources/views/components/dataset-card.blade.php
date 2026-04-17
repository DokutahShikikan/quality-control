@props(['dataset'])

@php
    $statusLabel = match ($dataset->import_status) {
        'queued' => 'В очереди',
        'processing' => 'Импортируется',
        'failed' => 'Ошибка импорта',
        default => $dataset->review_status === 'clean' ? 'Чисто' : 'Нужна проверка',
    };

    $statusTone = $dataset->import_status === 'ready' && $dataset->review_status === 'clean'
        ? 'clean'
        : 'review';
@endphp

<a href="/datasets/{{ $dataset->id }}" class="dataset-card">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h3 class="text-2xl font-black tracking-tight text-slate-950">{{ $dataset->name }}</h3>
            <p class="mt-2 text-sm text-slate-500">{{ $dataset->source_filename }}</p>
        </div>

        <x-status-pill :tone="$statusTone">
            {{ $statusLabel }}
        </x-status-pill>
    </div>

    <p class="mt-5 text-base leading-7 text-slate-700">
        {{ $dataset->description ?: 'Описание пока не добавлено. Набор загружен для проверки форматов, пустых значений и дубликатов.' }}
    </p>

    <div class="mt-6 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
        <x-mini-stat label="Строк" :value="$dataset->total_rows" />
        <x-mini-stat label="Колонок" :value="$dataset->total_columns" />
        <x-mini-stat label="Инцидентов" :value="data_get($dataset->metrics, 'open_issues', 0)" />
        <x-mini-stat label="Дубликатов" :value="data_get($dataset->metrics, 'open_duplicates', 0)" />
    </div>
</a>
