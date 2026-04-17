<x-layout title="Запуски проверок" current="checks">
    <div class="panel overflow-x-auto">
        <table class="data-table">
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
        </table>
    </div>
</x-layout>
