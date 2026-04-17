<x-layout title="Инциденты качества" current="issues">
    <div class="panel">
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
    </div>
</x-layout>
