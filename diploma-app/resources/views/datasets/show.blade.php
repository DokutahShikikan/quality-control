<x-layout :title="$dataset->name" current="datasets">
    <div class="space-y-8">
        <div class="metric-grid">
            <div class="metric-card">
                <div class="metric-label">Строк</div>
                <div class="metric-value">{{ $dataset->total_rows }}</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Открытые инциденты</div>
                <div class="metric-value">{{ data_get($dataset->metrics, 'open_issues', 0) }}</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Открытые дубликаты</div>
                <div class="metric-value">{{ data_get($dataset->metrics, 'open_duplicates', 0) }}</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Completeness rate</div>
                <div class="metric-value text-3xl">{{ data_get($dataset->metrics, 'completeness_rate', 0) }}%</div>
            </div>
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
                    <span class="status-pill {{ $dataset->review_status === 'clean' ? 'status-clean' : 'status-review' }}">
                        {{ $dataset->review_status === 'clean' ? 'Чистый набор' : 'Требует разбора' }}
                    </span>
                </div>

                <dl class="mt-8 grid gap-4 md:grid-cols-2">
                    <div class="mini-stat">
                        <span>Исходный файл</span>
                        <strong>{{ $dataset->source_filename }}</strong>
                    </div>
                    <div class="mini-stat">
                        <span>Последняя проверка</span>
                        <strong>{{ optional($dataset->last_checked_at)->format('d.m.Y H:i') ?: 'Еще не запускалась' }}</strong>
                    </div>
                    <div class="mini-stat">
                        <span>Format error rate</span>
                        <strong>{{ data_get($dataset->metrics, 'format_error_rate', 0) }}%</strong>
                    </div>
                    <div class="mini-stat">
                        <span>DeepSeek этап</span>
                        <strong>{{ data_get($dataset->metrics, 'deepseek_stage_ready', false) ? 'Можно запускать' : 'Сначала regex' }}</strong>
                    </div>
                </dl>

                <div class="mt-8 flex flex-wrap gap-4">
                    <form method="POST" action="/datasets/{{ $dataset->id }}/analyze">
                        @csrf
                        <button class="primary-button" type="submit">Запустить проверку заново</button>
                    </form>
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
            <div class="flex items-center justify-between gap-4">
                <h2 class="panel-title">Последние инциденты</h2>
                <a href="/issues" class="secondary-button">Все инциденты</a>
            </div>
            <div class="mt-6 overflow-x-auto">
                <table class="data-table">
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
                </table>
            </div>
        </section>

        <section class="panel">
            <div class="flex items-center justify-between gap-4">
                <h2 class="panel-title">Последние дубликаты</h2>
                <a href="/duplicates" class="secondary-button">Все дубликаты</a>
            </div>
            <div class="mt-6 overflow-x-auto">
                <table class="data-table">
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
                </table>
            </div>
        </section>
    </div>
</x-layout>
