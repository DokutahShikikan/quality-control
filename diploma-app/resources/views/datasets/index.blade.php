<x-layout title="Наборы данных" current="datasets">
    <section class="space-y-8">
        <div class="hero-panel">
            <div class="grid gap-8 xl:grid-cols-[1.2fr_0.8fr] xl:items-end">
                <div>
                    <div class="badge badge-info badge-outline rounded-full border-white/30 bg-white/10 px-4 py-3 text-white">Data Quality Workflow</div>
                    <h2 class="mt-5 text-3xl font-black tracking-tight text-white md:text-5xl">
                        От импорта Excel к управляемому исправлению ошибок и дублей
                    </h2>
                    <p class="mt-5 max-w-3xl text-base leading-8 text-blue-50 md:text-lg">
                        Пользователь загружает Excel или CSV, сервис находит пустые значения, нарушения формата по regex,
                        дубликаты строк и дает управляемые действия: исправить, игнорировать или передать спорные случаи на AI-этап.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="/datasets/create" class="primary-button">Импортировать таблицу</a>
                        <a href="/issues" class="secondary-button border-white/20 bg-white/10 text-white hover:bg-white/15">Открыть инциденты</a>
                    </div>
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    <div class="rounded-[24px] border border-white/15 bg-white/10 p-5 backdrop-blur">
                        <div class="text-xs font-bold uppercase tracking-[0.2em] text-blue-100">Regex layer</div>
                        <div class="mt-3 text-3xl font-black text-white">{{ $metrics['open_issues'] }}</div>
                        <p class="mt-2 text-sm leading-6 text-blue-100">Проблемы формата и пустые значения, которые можно разбирать детерминированно.</p>
                    </div>
                    <div class="rounded-[24px] border border-white/15 bg-white/10 p-5 backdrop-blur">
                        <div class="text-xs font-bold uppercase tracking-[0.2em] text-blue-100">Duplicate review</div>
                        <div class="mt-3 text-3xl font-black text-white">{{ $metrics['open_duplicates'] }}</div>
                        <p class="mt-2 text-sm leading-6 text-blue-100">Найденные кандидаты в дубли для ручного решения или удаления.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="metric-grid">
            <div class="metric-card">
                <div class="metric-label">Всего наборов</div>
                <div class="metric-value">{{ $metrics['datasets'] }}</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Открытые инциденты</div>
                <div class="metric-value">{{ $metrics['open_issues'] }}</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Кандидаты в дубликаты</div>
                <div class="metric-value">{{ $metrics['open_duplicates'] }}</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Готово к AI-этапу</div>
                <div class="metric-value">{{ $metrics['ready_for_ai'] }}</div>
            </div>
        </div>

        @if($datasets->isNotEmpty())
            <div class="grid grid-cols-1 gap-6 2xl:grid-cols-2">
                @foreach($datasets as $dataset)
                    <x-dataset-card :dataset="$dataset" />
                @endforeach
            </div>
        @else
            <div class="panel max-w-4xl">
                <h2 class="panel-title">Наборы пока не загружены</h2>
                <p class="mt-4 text-base leading-8 text-slate-600 md:text-lg">
                    Начни с импорта файла. Поддерживаются CSV и базовый XLSX. После загрузки набор автоматически проходит
                    первичную проверку и появляется в панели для последующего разбора.
                </p>
                <div class="mt-8">
                    <a href="/datasets/create" class="primary-button">Загрузить первый файл</a>
                </div>
            </div>
        @endif
    </section>
</x-layout>
