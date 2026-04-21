@php
    $triggerLabel = match ($latestRun?->trigger_source) {
        'import' => 'После загрузки',
        'manual' => 'Вручную',
        'regex_fix' => 'После исправления по шаблону',
        'duplicate_resolution' => 'После разбора повторов',
        'deepseek_fix' => 'После исправления через ИИ',
        default => 'Не указано',
    };

    $runStatusLabel = match ($latestRun?->status) {
        'completed' => 'Завершено',
        'running' => 'В процессе',
        'failed' => 'С ошибкой',
        default => 'Не указано',
    };
@endphp

<aside class="panel" id="dataset-status-card">
    <h3 class="soft-title">Последняя проверка</h3>
    @if($latestRun)
        <div class="mt-6 space-y-4 text-lg text-slate-700">
            <p><strong>Как запустили:</strong> {{ $triggerLabel }}</p>
            <p><strong>Состояние:</strong> {{ $runStatusLabel }}</p>
            <p><strong>Строк проверено:</strong> {{ $latestRun->total_rows }}</p>
            <p><strong>Ошибок найдено:</strong> {{ $latestRun->issues_count }}</p>
            <p><strong>Повторов найдено:</strong> {{ $latestRun->duplicate_pairs_count }}</p>
        </div>
    @else
        <p class="mt-6 text-lg text-slate-600">Проверка еще не запускалась.</p>
    @endif
</aside>
