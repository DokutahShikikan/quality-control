@props(['title' => '', 'current' => 'datasets'])
<!doctype html>
<html lang="ru" data-theme="corporate">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ? "{$title} | DQ System" : 'DQ System' }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="app-shell">
            <header class="app-header">
                <div>
                    <p class="badge badge-neutral badge-outline mb-3 rounded-full px-4 py-3">Rule-based Data Quality Platform</p>
                    <h1 class="app-title">{{ $title ?: 'Система контроля качества данных' }}</h1>
                    <p class="mt-4 max-w-3xl text-sm leading-7 text-slate-600 md:text-base">
                        Импорт таблиц, поиск проблем по regex, анализ дублей и подготовка спорных кейсов к AI-этапу.
                    </p>
                </div>
                <div class="app-brand">
                    <div class="text-xs uppercase tracking-[0.35em] text-slate-500">DQ System</div>
                    <div class="text-lg font-bold text-slate-800">Regex first, AI second</div>
                    <div class="text-sm text-slate-500">Adaptive workspace for data review</div>
                </div>
            </header>

            <div class="app-body">
                <x-forms.nav :current="$current" />

                <main class="content-area">
                    @if(session('success'))
                        <div class="alert alert-success mb-6 rounded-[22px] border border-emerald-200 bg-emerald-50/90 text-emerald-900 shadow-[0_14px_32px_rgba(16,185,129,0.10)]">
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-error mb-6 rounded-[22px] border border-rose-200 bg-rose-50/90 text-rose-900 shadow-[0_14px_32px_rgba(244,63,94,0.10)]">
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
