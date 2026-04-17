<x-layout title="Вход в систему" current="auth">
    <div class="mx-auto max-w-2xl">
        <div class="panel">
            <x-section-header
                title="Вход в систему"
                description="Открой рабочее пространство сервиса проверки, поиска дубликатов и автоисправления данных."
            />

            <form action="/login" method="POST" novalidate class="mt-8 space-y-6">
                @csrf

                <x-forms.input-field
                    name="email"
                    label="Email"
                    type="email"
                    placeholder="operator@dq.local"
                />

                <x-forms.input-field
                    name="password"
                    label="Пароль"
                    type="password"
                    placeholder="Введите пароль"
                />

                <x-form-actions>
                    <button class="primary-button" type="submit">Войти</button>
                    <a href="/register" class="secondary-button">Создать аккаунт</a>
                </x-form-actions>
            </form>
        </div>
    </div>
</x-layout>
