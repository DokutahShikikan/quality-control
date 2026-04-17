<x-layout title="Вход в систему" current="auth">
    <div class="mx-auto max-w-2xl">
        <div class="panel">
            <h2 class="panel-title">Вход в систему</h2>
            <p class="mt-3 text-lg text-slate-600">
                Открой рабочее пространство сервиса проверки, поиска дубликатов и автоисправления данных.
            </p>

            <form action="/login" method="POST" novalidate class="mt-8 space-y-6">
                @csrf

                <div class="form-field">
                    <label class="form-label" for="email">Email</label>
                    <input class="text-field" id="email" type="email" name="email" value="{{ old('email') }}" placeholder="operator@dq.local" required>
                    <x-forms.error name="email" />
                </div>

                <div class="form-field">
                    <label class="form-label" for="password">Пароль</label>
                    <input id="password" type="password" class="text-field" name="password" placeholder="Введите пароль" required>
                    <x-forms.error name="password" />
                </div>

                <div class="flex flex-wrap gap-4">
                    <button class="primary-button" type="submit">Войти</button>
                    <a href="/register" class="secondary-button">Создать аккаунт</a>
                </div>
            </form>
        </div>
    </div>
</x-layout>
