<x-layout title="Таблицы" current="datasets">
    <section class="space-y-8">
        <div class="hero-panel">
            <div class="grid gap-8 xl:grid-cols-[1.2fr_0.8fr] xl:items-end">
                <div>
                    <div class="badge badge-info badge-outline rounded-full border-white/30 bg-white/10 px-4 py-3 text-white">Проверка данных по шагам</div>
                    <h2 class="mt-5 text-3xl font-black tracking-tight text-white md:text-5xl">
                        От загрузки Excel к понятному исправлению ошибок и повторов
                    </h2>
                    <p class="mt-5 max-w-3xl text-base leading-8 text-blue-50 md:text-lg">
                        Пользователь загружает Excel или CSV, сервис находит пустые значения, нарушения формата,
                        повторы строк и даёт понятные действия: исправить, пропустить или передать спорные случаи в ИИ.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="/datasets/create" class="primary-button">Загрузить таблицу</a>
                        <a href="/issues" class="secondary-button border-white/20 bg-white/10 text-white hover:bg-white/15">Открыть ошибки</a>
                    </div>
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    <div class="rounded-[24px] border border-white/15 bg-white/10 p-5 backdrop-blur">
                        <div class="text-xs font-bold uppercase tracking-[0.2em] text-blue-100">Ошибки в данных</div>
                        <div class="mt-3 text-3xl font-black text-white">{{ $metrics['open_issues'] }}</div>
                        <p class="mt-2 text-sm leading-6 text-blue-100">Пустые ячейки и неверные форматы, которые можно разобрать сразу.</p>
                    </div>
                    <div class="rounded-[24px] border border-white/15 bg-white/10 p-5 backdrop-blur">
                        <div class="text-xs font-bold uppercase tracking-[0.2em] text-blue-100">Похожие строки</div>
                        <div class="mt-3 text-3xl font-black text-white">{{ $metrics['open_duplicates'] }}</div>
                        <p class="mt-2 text-sm leading-6 text-blue-100">Найденные повторы, которые нужно проверить и оставить одну верную строку.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="metric-grid">
            <x-metric-card label="Всего таблиц" :value="$metrics['datasets']" />
            <x-metric-card label="Открытые ошибки" :value="$metrics['open_issues']" />
            <x-metric-card label="Найдено повторов" :value="$metrics['open_duplicates']" />
            <x-metric-card label="Готово к ИИ" :value="$metrics['ready_for_ai']" />
        </div>

        <div class="panel">
            <form method="GET" action="/datasets" class="space-y-4">
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    <x-forms.input-field
                        class="md:col-span-2 xl:col-span-1"
                        name="q"
                        label="Поиск"
                        :value="$filters['q'] ?? ''"
                        placeholder="Название, описание или имя файла"
                    />

                    <label class="form-field">
                        <span class="form-label">Состояние таблицы</span>
                        <select name="review_status" class="text-field">
                            <option value="">Все</option>
                            <option value="needs_review" @selected(($filters['review_status'] ?? '') === 'needs_review')>Нужно проверить</option>
                            <option value="clean" @selected(($filters['review_status'] ?? '') === 'clean')>Без проблем</option>
                        </select>
                    </label>

                    <label class="form-field">
                        <span class="form-label">Сортировка</span>
                        <select name="sort" class="text-field">
                            <option value="newest" @selected(($filters['sort'] ?? '') === 'newest')>Сначала новые</option>
                            <option value="oldest" @selected(($filters['sort'] ?? '') === 'oldest')>Сначала старые</option>
                            <option value="most_issues" @selected(($filters['sort'] ?? '') === 'most_issues')>Недавно обновленные</option>
                        </select>
                    </label>
                </div>

                <x-form-actions>
                    <button type="submit" class="primary-button">Применить</button>
                    <a href="/datasets" class="secondary-button">Сбросить</a>
                </x-form-actions>
            </form>
        </div>

        @if($datasets->isNotEmpty())
            <div class="flex items-center justify-between gap-4">
                <p class="text-sm font-semibold uppercase tracking-[0.14em] text-slate-500">
                    Страница {{ $datasets->currentPage() }} из {{ $datasets->lastPage() }}
                </p>
                <p class="text-sm text-slate-500">
                    Показано {{ $datasets->firstItem() }}-{{ $datasets->lastItem() }} из {{ $datasets->total() }}
                </p>
            </div>

            <div class="grid grid-cols-1 gap-6 2xl:grid-cols-2">
                @foreach($datasets as $dataset)
                    <x-dataset-card :dataset="$dataset" />
                @endforeach
            </div>

            <div>
                {{ $datasets->onEachSide(1)->links() }}
            </div>
        @else
            <x-empty-state
                class="max-w-4xl"
                title="Таблицы не найдены"
                description="Попробуй изменить параметры поиска или загрузи новый файл для проверки."
            >
                <a href="/datasets/create" class="primary-button">Загрузить файл</a>
            </x-empty-state>
        @endif
    </section>
</x-layout>
