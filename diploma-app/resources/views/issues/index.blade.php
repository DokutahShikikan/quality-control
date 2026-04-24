<x-layout title="Ошибки в данных" current="issues">
    <div class="space-y-6">
        <div class="panel">
            <form method="GET" action="/issues" class="space-y-4">
                <div class="grid gap-4 md:grid-cols-2 2xl:grid-cols-5">
                    <x-forms.input-field
                        class="md:col-span-2 2xl:col-span-1"
                        name="q"
                        label="Поиск"
                        :value="$filters['q'] ?? ''"
                        placeholder="Таблица, колонка, значение или текст ошибки"
                    />

                    <label class="form-field">
                        <span class="form-label">Состояние</span>
                        <select name="status" class="text-field">
                            <option value="">Все</option>
                            @foreach($statusLabels as $value => $label)
                                <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="form-field">
                        <span class="form-label">Вид ошибки</span>
                        <select name="issue_type" class="text-field">
                            <option value="">Все</option>
                            @foreach($issueTypeLabels as $value => $label)
                                <option value="{{ $value }}" @selected(($filters['issue_type'] ?? '') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="form-field">
                        <span class="form-label">Важность</span>
                        <select name="severity" class="text-field">
                            <option value="">Все</option>
                            @foreach($severityLabels as $value => $label)
                                <option value="{{ $value }}" @selected(($filters['severity'] ?? '') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="form-field">
                        <span class="form-label">Сортировка</span>
                        <select name="sort" class="text-field">
                            <option value="newest" @selected(($filters['sort'] ?? '') === 'newest')>Сначала новые</option>
                            <option value="oldest" @selected(($filters['sort'] ?? '') === 'oldest')>Сначала старые</option>
                            <option value="severity" @selected(($filters['sort'] ?? '') === 'severity')>По важности</option>
                            <option value="status" @selected(($filters['sort'] ?? '') === 'status')>По состоянию</option>
                        </select>
                    </label>
                </div>

                <x-form-actions>
                    <button type="submit" class="primary-button">Применить</button>
                    <a href="/issues" class="secondary-button">Сбросить</a>
                </x-form-actions>
            </form>
        </div>

        <div
            data-issues-table-root
            data-refresh-url="/issues/table"
        >
            <div class="hidden mb-6 rounded-[22px] px-5 py-4 text-sm font-semibold" data-issues-feedback></div>
            @include('issues.partials.table-panel')
        </div>
    </div>
</x-layout>
