<x-layout title="Шаблоны проверки" current="rules">
    @php
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

    <div class="space-y-8">
        <section class="panel">
            <x-section-header
                title="Активные шаблоны проверки"
                description="Сначала сайт опирается на понятные шаблоны проверки и безопасные автоматические исправления. Этот слой помогает убрать очевидные ошибки до передачи спорных случаев в ИИ."
            />
        </section>

        <div class="panel">
            <x-data-table sticky>
                <thead>
                    <tr>
                        <th>Проверка</th>
                        <th>Тип проблемы</th>
                        <th>Важность</th>
                        <th>Для каких колонок</th>
                        <th>Описание</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rules as $rule)
                        @php
                            $columnHints = $rule->column_hints;

                            if (is_string($columnHints)) {
                                $decodedHints = json_decode($columnHints, true);
                                $columnHints = is_array($decodedHints) ? $decodedHints : [$columnHints];
                            }

                            $columnHints = is_array($columnHints) ? $columnHints : [];
                        @endphp
                        <tr>
                            <td>{{ $rule->name }}</td>
                            <td>{{ $issueTypeLabels[$rule->issue_type] ?? $rule->issue_type }}</td>
                            <td>{{ $severityLabels[$rule->severity] ?? $rule->severity }}</td>
                            <td>{{ implode(', ', $columnHints) }}</td>
                            <td>{{ $rule->description }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </x-data-table>
        </div>
    </div>
</x-layout>
