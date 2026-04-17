<x-layout title="Regex-правила" current="rules">
    <div class="space-y-8">
        <section class="panel">
            <h2 class="panel-title">Активные правила качества</h2>
            <p class="mt-4 text-lg leading-8 text-slate-700">
                На первом этапе сайт опирается на regex и предсказуемые нормализаторы. Это безопасный слой,
                который исправляет очевидные ошибки до передачи оставшихся спорных случаев в DeepSeek API.
            </p>
        </section>

        <div class="overflow-x-auto panel">
            <table class="data-table">
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
            </table>
        </div>
    </div>
</x-layout>
