@php
    $statusLabels = [
        'open' => 'Открыта',
        'fixed' => 'Исправлена',
        'ignored' => 'Пропущена',
    ];
@endphp

<section class="panel">
    <x-section-header title="Последние ошибки">
        <a href="/issues?dataset={{ $dataset->id }}" class="secondary-button">Все ошибки</a>
    </x-section-header>

    <x-data-table class="mt-6" sticky>
        <thead>
            <tr>
                <th>Колонка</th>
                <th>Что не так</th>
                <th>Было</th>
                <th>Предлагаем исправить</th>
                <th>Состояние</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dataset->issues as $issue)
                <tr>
                    <td>{{ $issue->column_name ?: 'Строка' }}</td>
                    <td>{{ $issue->title }}</td>
                    <td>{{ $issue->original_value ?: 'Пусто' }}</td>
                    <td>{{ $issue->suggested_value ?: 'Нет безопасного варианта' }}</td>
                    <td>{{ $statusLabels[$issue->status] ?? $issue->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-slate-500">Ошибок пока нет.</td>
                </tr>
            @endforelse
        </tbody>
    </x-data-table>
</section>
