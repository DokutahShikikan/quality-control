<x-layout :title="$dataset->name" current="datasets">
    @php
        $importLabel = match ($dataset->import_status) {
            'queued' => 'Файл стоит в очереди на импорт.',
            'processing' => 'Файл сейчас обрабатывается.',
            'failed' => 'Импорт завершился с ошибкой.',
            default => 'Импорт завершён.',
        };
    @endphp

    <div class="space-y-8">
        @if($dataset->import_status !== 'ready')
            <section class="panel">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="space-y-2">
                        <h2 class="panel-title">Статус импорта</h2>
                        <p class="text-base text-slate-700">{{ $importLabel }}</p>
                        @if($dataset->import_error)
                            <p class="text-sm text-rose-600">{{ $dataset->import_error }}</p>
                        @endif
                    </div>

                    <x-status-pill tone="review">
                        {{ strtoupper($dataset->import_status) }}
                    </x-status-pill>
                </div>
            </section>
        @endif

        <div class="metric-grid">
            <x-metric-card label="Строк" :value="$dataset->total_rows" />
            <x-metric-card label="Открытые инциденты" :value="data_get($dataset->metrics, 'open_issues', 0)" />
            <x-metric-card label="Открытые дубликаты" :value="data_get($dataset->metrics, 'open_duplicates', 0)" />
            <x-metric-card label="Completeness rate" :value="data_get($dataset->metrics, 'completeness_rate', 0).'%'"/>
        </div>

        <div class="grid gap-8 xl:grid-cols-[1.15fr_0.85fr]">
            <section class="panel">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <h2 class="panel-title">Паспорт набора</h2>
                        <p class="mt-4 text-lg leading-8 text-slate-700">
                            {{ $dataset->description ?: 'Описание не добавлено. Этот набор уже загружен и доступен для регулярных проверок.' }}
                        </p>
                    </div>

                    <x-status-pill :tone="$dataset->import_status === 'ready' && $dataset->review_status === 'clean' ? 'clean' : 'review'">
                        {{ $dataset->import_status === 'ready' ? ($dataset->review_status === 'clean' ? 'Чистый набор' : 'Требует разбора') : strtoupper($dataset->import_status) }}
                    </x-status-pill>
                </div>

                <dl class="mt-8 grid gap-4 md:grid-cols-2">
                    <x-mini-stat label="Исходный файл" :value="$dataset->source_filename" />
                    <x-mini-stat label="Статус импорта" :value="$dataset->import_status" />
                    <x-mini-stat label="Последняя проверка" :value="optional($dataset->last_checked_at)->format('d.m.Y H:i') ?: 'Еще не запускалась'" />
                    <x-mini-stat label="Format error rate" :value="data_get($dataset->metrics, 'format_error_rate', 0).'%'"/>
                    <x-mini-stat label="DeepSeek этап" :value="data_get($dataset->metrics, 'deepseek_stage_ready', false) ? 'Можно запускать' : 'Сначала regex'" />
                </dl>

                <div class="mt-8 flex flex-wrap gap-4">
                    @if($dataset->import_status === 'ready')
                        <form method="POST" action="/datasets/{{ $dataset->id }}/analyze">
                            @csrf
                            <button class="primary-button" type="submit">Запустить проверку заново</button>
                        </form>
                    @endif
                    <a href="/issues" class="secondary-button">Открыть инциденты</a>
                    <a href="/duplicates" class="secondary-button">Открыть дубликаты</a>
                    <form method="POST" action="/datasets/{{ $dataset->id }}">
                        @csrf
                        @method('DELETE')
                        <button class="danger-button" type="submit">Удалить набор</button>
                    </form>
                </div>
            </section>

            <aside class="panel">
                <h3 class="soft-title">Последний запуск</h3>
                @if($latestRun)
                    <div class="mt-6 space-y-4 text-lg text-slate-700">
                        <p><strong>Источник:</strong> {{ $latestRun->trigger_source }}</p>
                        <p><strong>Статус:</strong> {{ $latestRun->status }}</p>
                        <p><strong>Строк обработано:</strong> {{ $latestRun->total_rows }}</p>
                        <p><strong>Инцидентов найдено:</strong> {{ $latestRun->issues_count }}</p>
                        <p><strong>Дубликатов найдено:</strong> {{ $latestRun->duplicate_pairs_count }}</p>
                    </div>
                @else
                    <p class="mt-6 text-lg text-slate-600">Проверки еще не запускались.</p>
                @endif
            </aside>
        </div>

        <section class="panel">
            <x-section-header title="Последние инциденты">
                <a href="/issues" class="secondary-button">Все инциденты</a>
            </x-section-header>

            <x-data-table class="mt-6">
                <thead>
                    <tr>
                        <th>Колонка</th>
                        <th>Тип</th>
                        <th>Значение</th>
                        <th>Предложение</th>
                        <th>Статус</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dataset->issues as $issue)
                        <tr>
                            <td>{{ $issue->column_name ?: 'Строка' }}</td>
                            <td>{{ $issue->title }}</td>
                            <td>{{ $issue->original_value ?: 'Пусто' }}</td>
                            <td>{{ $issue->suggested_value ?: 'Нет безопасного исправления' }}</td>
                            <td>{{ $issue->status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-slate-500">Инцидентов пока нет.</td>
                        </tr>
                    @endforelse
                </tbody>
            </x-data-table>
        </section>

        <section class="panel">
            <x-section-header title="Последние дубликаты">
                <a href="/duplicates" class="secondary-button">Все дубликаты</a>
            </x-section-header>

            <x-data-table class="mt-6">
                <thead>
                    <tr>
                        <th>Базовая строка</th>
                        <th>Дубликат</th>
                        <th>Уверенность</th>
                        <th>Причина</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dataset->duplicateCandidates as $duplicate)
                        <tr>
                            <td>#{{ $duplicate->primaryRow->row_index }}</td>
                            <td>#{{ $duplicate->duplicateRow->row_index }}</td>
                            <td>{{ number_format($duplicate->confidence * 100, 0) }}%</td>
                            <td>{{ $duplicate->rationale }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-slate-500">Дубликаты не найдены.</td>
                        </tr>
                    @endforelse
                </tbody>
            </x-data-table>
        </section>
    </div>
</x-layout>
