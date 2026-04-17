@props(['current' => 'datasets'])

<aside class="sidebar">
    <nav class="sidebar-nav">
        <a href="/datasets" class="sidebar-link {{ $current === 'datasets' ? 'is-active' : '' }}">Наборы</a>
        <a href="/datasets/create" class="sidebar-link {{ $current === 'import' ? 'is-active' : '' }}">Импорт</a>
        <a href="/rules" class="sidebar-link {{ $current === 'rules' ? 'is-active' : '' }}">Regex</a>
        <a href="/checks" class="sidebar-link {{ $current === 'checks' ? 'is-active' : '' }}">Проверки</a>
        <a href="/issues" class="sidebar-link {{ $current === 'issues' ? 'is-active' : '' }}">Инциденты</a>
        <a href="/duplicates" class="sidebar-link {{ $current === 'duplicates' ? 'is-active' : '' }}">Дубликаты</a>
        <a href="/autofix" class="sidebar-link {{ $current === 'autofix' ? 'is-active' : '' }}">AI-этап</a>
    </nav>

    @auth
        <div class="glass-note mt-5">
            <div class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Аккаунт</div>
            <div class="mt-3 text-base font-bold text-slate-900">{{ auth()->user()->name }}</div>
            <div class="mt-1 text-sm text-slate-500">{{ auth()->user()->email }}</div>
        </div>

        <form method="POST" action="/logout" class="mt-4">
            @csrf
            @method('DELETE')
            <button class="secondary-button w-full" type="submit">Выйти</button>
        </form>
    @else
        <div class="mt-5 grid gap-3">
            <a href="/login" class="secondary-button w-full">Вход</a>
            <a href="/register" class="primary-button w-full">Регистрация</a>
        </div>
    @endauth
</aside>
