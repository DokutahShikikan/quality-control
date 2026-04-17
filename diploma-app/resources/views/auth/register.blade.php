<x-layout title="Регистрация пользователя" current="auth">
    <div class="mx-auto max-w-2xl">
        <div class="panel">
            <h2 class="panel-title">Регистрация пользователя</h2>
            <p class="mt-3 text-lg text-slate-600">
                Создай учетную запись для доступа к модулям проверки, отчетности и автоисправления данных.
            </p>

            <form action="/register" method="POST" novalidate class="mt-8 space-y-6">
                @csrf

                <div class="form-field">
                    <label class="form-label" for="name">Имя</label>
                    <input class="text-field" id="name" value="{{ old('name') }}" name="name" placeholder="Анна Петрова" required>
                    <x-forms.error name="name" />
                </div>

                <div class="form-field">
                    <label class="form-label" for="email">Email</label>
                    <input class="text-field" id="email" type="email" value="{{ old('email') }}" name="email" placeholder="analyst@dq.local" required>
                    <x-forms.error name="email" />
                </div>

                <div class="form-field">
                    <label class="form-label" for="password">Пароль</label>
                    <input id="password" type="password" class="text-field" name="password" placeholder="Минимум 8 символов" required>
                    <x-forms.error name="password" />
                </div>

                <div class="flex flex-wrap gap-4">
                    <button class="primary-button" type="submit">Зарегистрировать</button>
                    <a href="/login" class="secondary-button">Уже есть аккаунт</a>
                </div>
            </form>
        </div>
    </div>
</x-layout>
