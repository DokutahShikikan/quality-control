<x-layout title="Ошибки в данных" current="issues">
    @php
        $statusLabels = [
            'open' => 'Открыта',
            'fixed' => 'Исправлена',
            'ignored' => 'Пропущена',
        ];

        $issueTypeLabels = [
            'missing_value' => 'Пустое значение',
            'invalid_format' => 'Неверный формат',
            'out_of_range' => 'Недопустимое значение',
            'duplicate_value' => 'Повтор значения',
        ];

        $severityLabels = [
            'high' => 'Высокая',
            'medium' => 'Средняя',
            'low' => 'Низкая',
        ];
    @endphp

    <div class="space-y-6">
        <div class="panel">
            <form method="GET" action="/issues" class="space-y-4">
                <div class="grid gap-4 md:grid-cols-2 2xl:grid-cols-5">
                    <x-forms.input-field
                        class="md:col-span-2 2xl:col-span-1"
                        name="q"
                        label="Поиск"
                        :value="$filters['q'] ?? ''"
                        placeholder="Таблица, колонка, значение или текст ошибки"
                    />

                    <label class="form-field">
                        <span class="form-label">Состояние</span>
                        <select name="status" class="text-field">
                            <option value="">Все</option>
                            @foreach($statusLabels as $value => $label)
                                <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="form-field">
                        <span class="form-label">Вид ошибки</span>
                        <select name="issue_type" class="text-field">
                            <option value="">Все</option>
                            @foreach($issueTypeLabels as $value => $label)
                                <option value="{{ $value }}" @selected(($filters['issue_type'] ?? '') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="form-field">
                        <span class="form-label">Важность</span>
                        <select name="severity" class="text-field">
                            <option value="">Все</option>
                            @foreach($severityLabels as $value => $label)
                                <option value="{{ $value }}" @selected(($filters['severity'] ?? '') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="form-field">
                        <span class="form-label">Сортировка</span>
                        <select name="sort" class="text-field">
                            <option value="newest" @selected(($filters['sort'] ?? '') === 'newest')>Сначала новые</option>
                            <option value="oldest" @selected(($filters['sort'] ?? '') === 'oldest')>Сначала старые</option>
                            <option value="severity" @selected(($filters['sort'] ?? '') === 'severity')>По важности</option>
                            <option value="status" @selected(($filters['sort'] ?? '') === 'status')>По состоянию</option>
                        </select>
                    </label>
                </div>

                <x-form-actions>
                    <button type="submit" class="primary-button">Применить</button>
                    <a href="/issues" class="secondary-button">Сбросить</a>
                </x-form-actions>
            </form>
        </div>

        <div class="panel">
            @if($issues->total() > 0)
                <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm font-semibold uppercase tracking-[0.14em] text-slate-500">
                        Страница {{ $issues->currentPage() }} из {{ $issues->lastPage() }}
                    </p>
                    <p class="text-sm text-slate-500">
                        Показано {{ $issues->firstItem() }}-{{ $issues->lastItem() }} из {{ $issues->total() }}
                    </p>
                </div>
            @endif

            <x-data-table>
                <thead>
                    <tr>
                        <th>Таблица</th>
                        <th>Строка</th>
                        <th>Колонка</th>
                        <th>Что не так</th>
                        <th>Было</th>
                        <th>Предлагаем исправить</th>
                        <th>Состояние</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($issues as $issue)
                        @php
                            $currentValue = $issue->original_value ?: 'Пусто';
                            $nextValue = $issue->suggested_value ?: 'Нет безопасного автоматического исправления';
                        @endphp
                        <tr>
                            <td><a href="/datasets/{{ $issue->dataset_id }}" class="link link-hover">{{ $issue->dataset->name }}</a></td>
                            <td>#{{ data_get($issue->meta, 'row_index', '-') }}</td>
                            <td>{{ $issue->column_name ?: 'Строка' }}</td>
                            <td>{{ $issue->title }}</td>
                            <td>{{ $issue->original_value ?: 'Пусто' }}</td>
                            <td>{{ $issue->suggested_value ?: 'Нет' }}</td>
                            <td>{{ $statusLabels[$issue->status] ?? $issue->status }}</td>
                            <td>
                                <div class="flex flex-wrap gap-2">
                                    <form method="POST" action="/issues/{{ $issue->id }}/fix">
                                        @csrf
                                        <span class="tooltip-trigger">
                                            <button
                                                class="btn btn-sm rounded-none btn-primary action-button"
                                                type="submit"
                                                aria-label="Исправить значение"
                                                {{ $issue->status !== 'open' || ! $issue->suggested_value ? 'disabled' : '' }}
                                            >Исправить</button>

                                            @if($issue->suggested_value)
                                                <span class="tooltip-card">
                                                    <span class="tooltip-label">Сейчас</span>
                                                    <span class="tooltip-value">{{ $currentValue }}</span>
                                                    <span class="tooltip-arrow-line">
                                                        <span>Поменяем</span>
                                                        <span aria-hidden="true">→</span>
                                                    </span>
                                                    <span class="tooltip-label">Станет</span>
                                                    <span class="tooltip-value">{{ $nextValue }}</span>
                                                </span>
                                            @endif
                                        </span>
                                    </form>
                                    <form method="POST" action="/issues/{{ $issue->id }}/ignore">
                                        @csrf
                                        <button class="btn btn-sm rounded-none btn-ghost border border-slate-300 action-button" type="submit" {{ $issue->status !== 'open' ? 'disabled' : '' }}>Пропустить</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-slate-500">Ошибки не найдены.</td>
                        </tr>
                    @endforelse
                </tbody>
            </x-data-table>

            @if($issues->total() > 0)
                <div class="mt-6">
                    {{ $issues->onEachSide(1)->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layout>
