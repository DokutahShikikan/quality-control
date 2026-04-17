<x-layout title="Инциденты качества" current="issues">
    <div class="space-y-6">
        <div class="panel">
            <form method="GET" action="/issues" class="space-y-4">
                <div class="grid gap-4 md:grid-cols-2 2xl:grid-cols-5">
                    <x-forms.input-field
                        class="md:col-span-2 2xl:col-span-1"
                        name="q"
                        label="Поиск"
                        :value="$filters['q'] ?? ''"
                        placeholder="Набор, колонка, значение, текст ошибки"
                    />

                    <label class="form-field">
                        <span class="form-label">Статус</span>
                        <select name="status" class="text-field">
                            <option value="">Все</option>
                            @foreach(['open' => 'Открыт', 'fixed' => 'Исправлен', 'ignored' => 'Игнорирован'] as $value => $label)
                                <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="form-field">
                        <span class="form-label">Тип</span>
                        <select name="issue_type" class="text-field">
                            <option value="">Все</option>
                            @foreach(['missing_value' => 'Пустые значения', 'invalid_format' => 'Неверный формат'] as $value => $label)
                                <option value="{{ $value }}" @selected(($filters['issue_type'] ?? '') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="form-field">
                        <span class="form-label">Критичность</span>
                        <select name="severity" class="text-field">
                            <option value="">Все</option>
                            @foreach(['high' => 'Высокая', 'medium' => 'Средняя', 'low' => 'Низкая'] as $value => $label)
                                <option value="{{ $value }}" @selected(($filters['severity'] ?? '') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="form-field">
                        <span class="form-label">Сортировка</span>
                        <select name="sort" class="text-field">
                            <option value="newest" @selected(($filters['sort'] ?? '') === 'newest')>Сначала новые</option>
                            <option value="oldest" @selected(($filters['sort'] ?? '') === 'oldest')>Сначала старые</option>
                            <option value="severity" @selected(($filters['sort'] ?? '') === 'severity')>По критичности</option>
                            <option value="status" @selected(($filters['sort'] ?? '') === 'status')>По статусу</option>
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
                        <th>Набор</th>
                        <th>Строка</th>
                        <th>Колонка</th>
                        <th>Проблема</th>
                        <th>Значение</th>
                        <th>Предложение</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($issues as $issue)
                        <tr>
                            <td><a href="/datasets/{{ $issue->dataset_id }}" class="link link-hover">{{ $issue->dataset->name }}</a></td>
                            <td>#{{ data_get($issue->meta, 'row_index', '-') }}</td>
                            <td>{{ $issue->column_name ?: 'Строка' }}</td>
                            <td>{{ $issue->title }}</td>
                            <td>{{ $issue->original_value ?: 'Пусто' }}</td>
                            <td>{{ $issue->suggested_value ?: 'Нет' }}</td>
                            <td>{{ $issue->status }}</td>
                            <td>
                                <div class="flex flex-wrap gap-2">
                                    <form method="POST" action="/issues/{{ $issue->id }}/fix">
                                        @csrf
                                        <button class="btn btn-sm rounded-none btn-primary" type="submit" {{ $issue->status !== 'open' || !$issue->suggested_value ? 'disabled' : '' }}>Исправить</button>
                                    </form>
                                    <form method="POST" action="/issues/{{ $issue->id }}/ignore">
                                        @csrf
                                        <button class="btn btn-sm rounded-none btn-ghost border border-slate-300" type="submit" {{ $issue->status !== 'open' ? 'disabled' : '' }}>Игнорировать</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-slate-500">Инциденты не найдены.</td>
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
