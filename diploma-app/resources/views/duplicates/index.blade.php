<x-layout title="Повторы" current="duplicates">
    @php
        $statusLabels = [
            'open' => 'Открыт',
            'fixed' => 'Исправлен',
            'ignored' => 'Пропущен',
        ];
    @endphp

    <div class="space-y-6">
        <div class="panel">
            <form method="GET" action="/duplicates" class="space-y-4">
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    <x-forms.input-field
                        class="md:col-span-2 xl:col-span-1"
                        name="q"
                        label="Поиск"
                        :value="$filters['q'] ?? ''"
                        placeholder="Таблица или причина совпадения"
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
                        <span class="form-label">Сортировка</span>
                        <select name="sort" class="text-field">
                            <option value="newest" @selected(($filters['sort'] ?? '') === 'newest')>Сначала новые</option>
                            <option value="oldest" @selected(($filters['sort'] ?? '') === 'oldest')>Сначала старые</option>
                            <option value="confidence_high" @selected(($filters['sort'] ?? '') === 'confidence_high')>С высокой уверенностью</option>
                            <option value="confidence_low" @selected(($filters['sort'] ?? '') === 'confidence_low')>С низкой уверенностью</option>
                        </select>
                    </label>
                </div>

                <x-form-actions>
                    <button type="submit" class="primary-button">Применить</button>
                    <a href="/duplicates" class="secondary-button">Сбросить</a>
                </x-form-actions>
            </form>
        </div>

        <div class="panel">
            @if($duplicates->total() > 0)
                <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm font-semibold uppercase tracking-[0.14em] text-slate-500">
                        Страница {{ $duplicates->currentPage() }} из {{ $duplicates->lastPage() }}
                    </p>
                    <p class="text-sm text-slate-500">
                        Показано {{ $duplicates->firstItem() }}-{{ $duplicates->lastItem() }} из {{ $duplicates->total() }}
                    </p>
                </div>
            @endif

            <x-data-table sticky>
                <thead>
                    <tr>
                        <th>Таблица</th>
                        <th>Основная строка</th>
                        <th>Повторяющаяся строка</th>
                        <th>Уверенность</th>
                        <th>Почему это повтор</th>
                        <th>Состояние</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($duplicates as $duplicate)
                        <tr>
                            <td><a href="/datasets/{{ $duplicate->dataset_id }}" class="link link-hover">{{ $duplicate->dataset->name }}</a></td>
                            <td>#{{ $duplicate->primaryRow->row_index }}</td>
                            <td>#{{ $duplicate->duplicateRow->row_index }}</td>
                            <td>{{ number_format($duplicate->confidence * 100, 0) }}%</td>
                            <td>{{ $duplicate->rationale }}</td>
                            <td>{{ $statusLabels[$duplicate->status] ?? $duplicate->status }}</td>
                            <td>
                                <div class="table-actions duplicate-actions">
                                    <form method="POST" action="/duplicates/{{ $duplicate->id }}/fix" class="table-action-form">
                                        @csrf
                                        <button class="table-primary-button action-button" type="submit" {{ $duplicate->status !== 'open' ? 'disabled' : '' }}>Удалить повтор</button>
                                    </form>
                                    <form method="POST" action="/duplicates/{{ $duplicate->id }}/fix-group" class="table-action-form">
                                        @csrf
                                        <button class="table-secondary-button action-button" type="submit" {{ $duplicate->status !== 'open' ? 'disabled' : '' }}>Удалить все повторы</button>
                                    </form>
                                    <form method="POST" action="/duplicates/{{ $duplicate->id }}/ignore" class="table-action-form">
                                        @csrf
                                        <button class="table-secondary-button action-button" type="submit" {{ $duplicate->status !== 'open' ? 'disabled' : '' }}>Игнорировать</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-slate-500">Повторы не найдены.</td>
                        </tr>
                    @endforelse
                </tbody>
            </x-data-table>

            @if($duplicates->total() > 0)
                <div class="mt-6">
                    {{ $duplicates->onEachSide(1)->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layout>
