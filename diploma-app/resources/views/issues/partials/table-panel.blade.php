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

    <x-data-table sticky>
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
                <tr>
                    <td><a href="/datasets/{{ $issue->dataset_id }}" class="link link-hover">{{ $issue->dataset->name }}</a></td>
                    <td>#{{ data_get($issue->meta, 'row_index', '-') }}</td>
                    <td>{{ $issue->column_name ?: 'Строка' }}</td>
                    <td>{{ $issue->title }}</td>
                    <td>{{ $issue->original_value ?: 'Пусто' }}</td>
                    <td>{{ $issue->suggested_value ?: 'Нет' }}</td>
                    <td>{{ $statusLabels[$issue->status] ?? $issue->status }}</td>
                    <td class="align-top">
                        <div class="table-actions issue-actions">
                            <form method="POST" action="/issues/{{ $issue->id }}/fix" class="table-action-form" data-issues-action-form>
                                @csrf
                                <input type="hidden" name="dataset_id" value="{{ $issue->dataset_id }}">
                                <input type="hidden" name="dataset_row_id" value="{{ $issue->dataset_row_id }}">
                                <input type="hidden" name="column_name" value="{{ $issue->column_name }}">
                                <input type="hidden" name="suggested_value" value="{{ $issue->suggested_value }}">
                                <button
                                    class="table-primary-button action-button"
                                    type="submit"
                                    aria-label="Исправить значение"
                                    {{ $issue->status !== 'open' || ! $issue->suggested_value ? 'disabled' : '' }}
                                >Исправить</button>
                            </form>
                            <form method="POST" action="/issues/{{ $issue->id }}/fix-similar" class="table-action-form" data-issues-action-form>
                                @csrf
                                <button class="table-secondary-button action-button min-h-14 px-3 py-3" type="submit" {{ $issue->status !== 'open' || ! $issue->suggested_value ? 'disabled' : '' }}>Исправить все подобные ошибки</button>
                            </form>
                            <form method="POST" action="/issues/{{ $issue->id }}/ignore" class="table-action-form" data-issues-action-form>
                                @csrf
                                <button class="table-secondary-button action-button" type="submit" {{ $issue->status !== 'open' ? 'disabled' : '' }}>Пропустить</button>
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
