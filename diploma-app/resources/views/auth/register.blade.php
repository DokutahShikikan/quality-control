<x-layout title="Регистрация пользователя" current="auth">
    <div class="mx-auto max-w-2xl">
        <div class="panel">
            <x-section-header
                title="Регистрация пользователя"
                description="Создай учетную запись для доступа к модулям проверки, отчетности и автоисправления данных."
            />

            <form action="/register" method="POST" novalidate class="mt-8 space-y-6">
                @csrf

                <x-forms.input-field
                    name="name"
                    label="Имя"
                    placeholder="Анна Петрова"
                />

                <x-forms.input-field
                    name="email"
                    label="Email"
                    type="email"
                    placeholder="analyst@dq.local"
                />

                <x-forms.input-field
                    name="password"
                    label="Пароль"
                    type="password"
                    placeholder="Минимум 8 символов"
                />

                <x-form-actions>
                    <button class="primary-button" type="submit">Зарегистрировать</button>
                    <a href="/login" class="secondary-button">Уже есть аккаунт</a>
                </x-form-actions>
            </form>
        </div>
    </div>
</x-layout>
