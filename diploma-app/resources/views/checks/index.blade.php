<x-layout title="Запуски проверок" current="checks">
    <div class="space-y-6">
        <div class="panel">
            <form method="GET" action="/checks" class="space-y-4">
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <x-forms.input-field
                        class="md:col-span-2 xl:col-span-1"
                        name="q"
                        label="Поиск"
                        :value="$filters['q'] ?? ''"
                        placeholder="Набор, источник запуска или статус"
                    />

                    <label class="form-field">
                        <span class="form-label">Статус</span>
                        <select name="status" class="text-field">
                            <option value="">Все</option>
                            @foreach(['completed' => 'Завершен', 'running' => 'В процессе'] as $value => $label)
                                <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="form-field">
                        <span class="form-label">Источник</span>
                        <select name="trigger_source" class="text-field">
                            <option value="">Все</option>
                            @foreach(['import' => 'Импорт', 'manual' => 'Ручной запуск', 'regex_fix' => 'Regex-исправление', 'duplicate_resolution' => 'Разбор дублей'] as $value => $label)
                                <option value="{{ $value }}" @selected(($filters['trigger_source'] ?? '') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="form-field">
                        <span class="form-label">Сортировка</span>
                        <select name="sort" class="text-field">
                            <option value="newest" @selected(($filters['sort'] ?? '') === 'newest')>Сначала новые</option>
                            <option value="oldest" @selected(($filters['sort'] ?? '') === 'oldest')>Сначала старые</option>
                            <option value="issues" @selected(($filters['sort'] ?? '') === 'issues')>По числу инцидентов</option>
                            <option value="duplicates" @selected(($filters['sort'] ?? '') === 'duplicates')>По числу дублей</option>
                        </select>
                    </label>
                </div>

                <x-form-actions>
                    <button type="submit" class="primary-button">Применить</button>
                    <a href="/checks" class="secondary-button">Сбросить</a>
                </x-form-actions>
            </form>
        </div>

        <div class="panel">
            @if($runs->total() > 0)
                <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm font-semibold uppercase tracking-[0.14em] text-slate-500">
                        Страница {{ $runs->currentPage() }} из {{ $runs->lastPage() }}
                    </p>
                    <p class="text-sm text-slate-500">
                        Показано {{ $runs->firstItem() }}-{{ $runs->lastItem() }} из {{ $runs->total() }}
                    </p>
                </div>
            @endif

            <x-data-table>
                <thead>
                    <tr>
                        <th>Набор</th>
                        <th>Источник</th>
                        <th>Статус</th>
                        <th>Строк</th>
                        <th>Инцидентов</th>
                        <th>Дубликатов</th>
                        <th>Дата</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($runs as $run)
                        <tr>
                            <td><a href="/datasets/{{ $run->dataset_id }}" class="link link-hover">{{ $run->dataset->name }}</a></td>
                            <td>{{ $run->trigger_source }}</td>
                            <td>{{ $run->status }}</td>
                            <td>{{ $run->total_rows }}</td>
                            <td>{{ $run->issues_count }}</td>
                            <td>{{ $run->duplicate_pairs_count }}</td>
                            <td>{{ $run->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-slate-500">Запусков пока нет.</td>
                        </tr>
                    @endforelse
                </tbody>
            </x-data-table>

            @if($runs->total() > 0)
                <div class="mt-6">
                    {{ $runs->onEachSide(1)->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layout>
