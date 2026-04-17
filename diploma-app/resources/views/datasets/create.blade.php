<x-layout title="Импорт набора" current="import">
    <div class="grid gap-8 xl:grid-cols-[1.2fr_0.8fr]">
        <section class="panel">
            <h2 class="panel-title">Загрузка исходной таблицы</h2>
            <p class="mt-4 text-lg leading-8 text-slate-700">
                Загрузи Excel или CSV с проблемными данными. Сразу после сохранения система создаст строки набора,
                выполнит regex-проверки и сформирует список ошибок и дубликатов.
            </p>

            <form method="POST" action="/datasets" enctype="multipart/form-data" class="mt-8 space-y-6">
                @csrf

                <label class="form-field">
                    <span class="form-label">Название набора</span>
                    <input class="text-field" type="text" name="name" value="{{ old('name') }}" placeholder="Например, Клиенты апрель 2026">
                    <x-forms.error name="name" />
                </label>

                <label class="form-field">
                    <span class="form-label">Описание задачи</span>
                    <textarea class="text-area" name="description" rows="5" placeholder="Что ожидается проверить: email, телефоны, даты, дубликаты...">{{ old('description') }}</textarea>
                    <x-forms.error name="description" />
                </label>

                <label class="form-field">
                    <span class="form-label">Файл данных</span>
                    <input class="file-input w-full border border-[#c7d4e6] bg-white text-lg" type="file" name="source_file" accept=".csv,.txt,.xlsx">
                    <x-forms.error name="source_file" />
                </label>

                <label class="flex items-center gap-3 rounded-none border border-[#c7d4e6] bg-[#f7fbff] px-4 py-4 text-lg text-slate-700">
                    <input class="checkbox checkbox-primary rounded-none" type="checkbox" name="deepseek_enabled" value="1" {{ old('deepseek_enabled') ? 'checked' : '' }}>
                    <span>Подготовить набор к AI-этапу с DeepSeek после regex-исправлений</span>
                </label>

                <div class="flex flex-wrap gap-4">
                    <button type="submit" class="primary-button">Сохранить и проверить</button>
                    <a href="/datasets" class="secondary-button">Назад к наборам</a>
                </div>
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
