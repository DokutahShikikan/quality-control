@props(['dataset'])

<a href="/datasets/{{ $dataset->id }}" class="dataset-card">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h3 class="text-2xl font-black tracking-tight text-slate-950">{{ $dataset->name }}</h3>
            <p class="mt-2 text-sm text-slate-500">{{ $dataset->source_filename }}</p>
        </div>
        <span class="status-pill {{ $dataset->review_status === 'clean' ? 'status-clean' : 'status-review' }}">
            {{ $dataset->review_status === 'clean' ? 'Чисто' : 'Нужна проверка' }}
        </span>
    </div>

    <p class="mt-5 text-base leading-7 text-slate-700">
        {{ $dataset->description ?: 'Описание пока не добавлено. Набор загружен для проверки форматов, пустых значений и дубликатов.' }}
    </p>

    <div class="mt-6 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
        <div class="mini-stat">
            <span>Строк</span>
            <strong>{{ $dataset->total_rows }}</strong>
        </div>
        <div class="mini-stat">
            <span>Колонок</span>
            <strong>{{ $dataset->total_columns }}</strong>
        </div>
        <div class="mini-stat">
            <span>Инцидентов</span>
            <strong>{{ data_get($dataset->metrics, 'open_issues', 0) }}</strong>
        </div>
        <div class="mini-stat">
            <span>Дубликатов</span>
            <strong>{{ data_get($dataset->metrics, 'open_duplicates', 0) }}</strong>
        </div>
    </div>
</a>
