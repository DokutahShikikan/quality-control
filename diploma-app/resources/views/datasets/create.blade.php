<x-layout title="Загрузка таблицы" current="import">
    <div class="grid gap-8 xl:grid-cols-[1.2fr_0.8fr]">
        <section class="panel">
            <x-section-header
                title="Загрузка исходной таблицы"
                description="Загрузи Excel или CSV с проблемными данными. После сохранения система разберет строки, выполнит проверку по шаблонам и соберет список ошибок и повторов."
            />

            <form method="POST" action="/datasets" enctype="multipart/form-data" class="mt-8 space-y-6">
                @csrf

                <x-forms.input-field
                    name="name"
                    label="Название таблицы"
                    placeholder="Например, Клиенты апрель 2026"
                />

                <x-forms.textarea-field
                    name="description"
                    label="Что нужно проверить"
                    placeholder="Что ожидается проверить: почту, телефоны, даты, повторы..."
                />

                <x-forms.file-field
                    name="source_file"
                    label="Файл с данными"
                    accept=".csv,.txt,.xlsx"
                />

                <x-forms.checkbox-card
                    name="deepseek_enabled"
                    label="Подготовить таблицу к шагу с ИИ после исправления понятных ошибок"
                />

                <x-form-actions>
                    <button type="submit" class="primary-button">Сохранить и проверить</button>
                    <a href="/datasets" class="secondary-button">Назад к таблицам</a>
                </x-form-actions>
            </form>
        </section>

        <aside class="panel">
            <h3 class="soft-title">Что произойдет после загрузки</h3>
            <div class="mt-6 space-y-4 text-lg leading-8 text-slate-700">
                <p>1. Таблица разберется на заголовки и строки.</p>
                <p>2. Для строк построятся нормализованные отпечатки для поиска полных повторов.</p>
                <p>3. Включатся шаблоны проверки для почты, телефонов и дат.</p>
                <p>4. Пустые значения и нарушения формата попадут в список ошибок.</p>
                <p>5. Дальше ты выберешь: исправить автоматически, пропустить или передать спорные случаи в ИИ.</p>
            </div>
        </aside>
    </div>
</x-layout>
