<x-layout :title="$dataset->name" current="datasets">
    @php
        $importLabel = match ($dataset->import_status) {
            'queued' => 'Файл ждёт своей очереди на загрузку.',
            'processing' => 'Файл сейчас загружается и проверяется.',
            'failed' => 'Во время загрузки произошла ошибка.',
            default => 'Файл загружен.',
        };

        $importStateLabel = match ($dataset->import_status) {
            'queued' => 'В очереди',
            'processing' => 'Загружается',
            'failed' => 'Ошибка загрузки',
            'ready' => 'Готово',
            default => $dataset->import_status,
        };
    @endphp

    <div
        class="space-y-8"
        data-live-panels
        data-refresh-url="/datasets/{{ $dataset->id }}/live-panels"
        data-refresh-interval="5000"
    >
        @if($dataset->import_status !== 'ready')
            <section class="panel">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="space-y-2">
                        <h2 class="panel-title">Состояние загрузки</h2>
                        <p class="text-base text-slate-700">{{ $importLabel }}</p>
                        @if($dataset->import_error)
                            <p class="text-sm text-rose-600">{{ $dataset->import_error }}</p>
                        @endif
                    </div>

                    <x-status-pill tone="review">
                        {{ $importStateLabel }}
                    </x-status-pill>
                </div>
            </section>
        @endif

        <div class="metric-grid">
            <x-metric-card label="Строк" :value="$dataset->total_rows" />
            <x-metric-card label="Найдено ошибок" :value="data_get($dataset->metrics, 'open_issues', 0)" />
            <x-metric-card label="Найдено повторов" :value="data_get($dataset->metrics, 'open_duplicates', 0)" />
            <x-metric-card label="Заполнено данных" :value="data_get($dataset->metrics, 'completeness_rate', 0).'%'"/>
        </div>

        <div class="grid gap-8 xl:grid-cols-[1.15fr_0.85fr]">
            <section class="panel">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <h2 class="panel-title">О таблице</h2>
                        <p class="mt-4 text-lg leading-8 text-slate-700">
                            {{ $dataset->description ?: 'Описание не добавлено. Таблица загружена для поиска пустых ячеек, неверных форматов и повторов.' }}
                        </p>
                    </div>

                    <x-status-pill :tone="$dataset->import_status === 'ready' && $dataset->review_status === 'clean' ? 'clean' : 'review'">
                        {{ $dataset->import_status === 'ready' ? ($dataset->review_status === 'clean' ? 'Проблем не найдено' : 'Нужно проверить') : $importStateLabel }}
                    </x-status-pill>
                </div>

                <dl class="mt-8 grid gap-4 md:grid-cols-2">
                    <x-mini-stat label="Исходный файл" :value="$dataset->source_filename" />
                    <x-mini-stat label="Состояние загрузки" :value="$importStateLabel" />
                    <x-mini-stat label="Последняя проверка" :value="optional($dataset->last_checked_at)->format('d.m.Y H:i') ?: 'Еще не запускалась'" />
                    <x-mini-stat label="Ошибок по формату" :value="data_get($dataset->metrics, 'format_error_rate', 0).'%'"/>
                    <x-mini-stat label="Следующий шаг" :value="data_get($dataset->metrics, 'deepseek_stage_ready', false) ? 'Можно включать ИИ' : 'Сначала исправить понятные ошибки'" />
                </dl>

                <div class="mt-8 flex flex-wrap gap-4">
                    @if($dataset->import_status === 'ready')
                        <form method="POST" action="/datasets/{{ $dataset->id }}/analyze">
                            @csrf
                            <button class="primary-button" type="submit">Запустить проверку заново</button>
                        </form>
                    @endif
                    <a href="/issues" class="secondary-button">Открыть ошибки</a>
                    <a href="/duplicates" class="secondary-button">Открыть повторы</a>
                    <form method="POST" action="/datasets/{{ $dataset->id }}">
                        @csrf
                        @method('DELETE')
                        <button class="danger-button" type="submit">Удалить таблицу</button>
                    </form>
                </div>
            </section>

            <div data-live-target="statsHtml">
                @include('datasets.partials.dataset-status-card', ['dataset' => $dataset, 'latestRun' => $latestRun])
            </div>
        </div>

        <div data-live-target="issuesHtml">
            @include('datasets.partials.recent-errors-table', ['dataset' => $dataset])
        </div>

        <div data-live-target="duplicatesHtml">
            @include('datasets.partials.recent-duplicates-table', ['dataset' => $dataset])
        </div>
    </div>
</x-layout>
