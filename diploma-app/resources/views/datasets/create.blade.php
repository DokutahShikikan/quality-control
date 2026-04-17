<x-layout title="Импорт набора" current="import">
    <div class="grid gap-8 xl:grid-cols-[1.2fr_0.8fr]">
        <section class="panel">
            <x-section-header
                title="Загрузка исходной таблицы"
                description="Загрузи Excel или CSV с проблемными данными. Сразу после сохранения система создаст строки набора, выполнит regex-проверки и сформирует список ошибок и дубликатов."
            />

            <form method="POST" action="/datasets" enctype="multipart/form-data" class="mt-8 space-y-6">
                @csrf

                <x-forms.input-field
                    name="name"
                    label="Название набора"
                    placeholder="Например, Клиенты апрель 2026"
                />

                <x-forms.textarea-field
                    name="description"
                    label="Описание задачи"
                    placeholder="Что ожидается проверить: email, телефоны, даты, дубликаты..."
                />

                <x-forms.file-field
                    name="source_file"
                    label="Файл данных"
                    accept=".csv,.txt,.xlsx"
                />

                <x-forms.checkbox-card
                    name="deepseek_enabled"
                    label="Подготовить набор к AI-этапу с DeepSeek после regex-исправлений"
                />

                <x-form-actions>
                    <button type="submit" class="primary-button">Сохранить и проверить</button>
                    <a href="/datasets" class="secondary-button">Назад к наборам</a>
                </x-form-actions>
            </form>
        </section>

        <aside class="panel">
            <h3 class="soft-title">Что произойдет после загрузки</h3>
            <div class="mt-6 space-y-4 text-lg leading-8 text-slate-700">
                <p>1. Таблица разбирается на заголовки и строки.</p>
                <p>2. Для строк строятся нормализованные отпечатки для поиска полных дублей.</p>
                <p>3. Включаются regex-правила для email, телефонов и дат.</p>
                <p>4. Пустые значения и нарушения формата попадают в инциденты.</p>
                <p>5. Дальше ты выбираешь: исправить безопасно, проигнорировать или запускать AI-этап.</p>
            </div>
        </aside>
    </div>
</x-layout>
