<x-layout title="Regex-правила" current="rules">
    <div class="space-y-8">
        <section class="panel">
            <x-section-header
                title="Активные правила качества"
                description="На первом этапе сайт опирается на regex и предсказуемые нормализаторы. Это безопасный слой, который исправляет очевидные ошибки до передачи оставшихся спорных случаев в DeepSeek API."
            />
        </section>

        <div class="panel">
            <x-data-table>
                <thead>
                    <tr>
                        <th>Правило</th>
                        <th>Тип проблемы</th>
                        <th>Критичность</th>
                        <th>Подсказки по колонкам</th>
                        <th>Описание</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rules as $rule)
                        <tr>
                            <td>{{ $rule->name }}</td>
                            <td>{{ $rule->issue_type }}</td>
                            <td>{{ $rule->severity }}</td>
                            <td>{{ implode(', ', $rule->column_hints ?? []) }}</td>
                            <td>{{ $rule->description }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </x-data-table>
        </div>
    </div>
</x-layout>
