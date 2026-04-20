<section class="panel">
    <x-section-header title="Последние повторы">
        <a href="/duplicates" class="secondary-button">Все повторы</a>
    </x-section-header>

    <x-data-table class="mt-6">
        <thead>
            <tr>
                <th>Основная строка</th>
                <th>Похожая строка</th>
                <th>Насколько похоже</th>
                <th>Почему решили, что это повтор</th>
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
                    <td colspan="4" class="text-center text-slate-500">Повторы не найдены.</td>
                </tr>
            @endforelse
        </tbody>
    </x-data-table>
</section>
