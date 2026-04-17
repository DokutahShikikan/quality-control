<x-layout title="Запуски проверок" current="checks">
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
</x-layout>
