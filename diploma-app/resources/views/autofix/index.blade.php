<x-layout title="Автоисправление" current="autofix">
    <div class="grid gap-8 xl:grid-cols-[1fr_1fr]">
        <section class="panel">
            <x-section-header title="Как работает исправление" />
            <div class="mt-6 space-y-4 text-lg leading-8 text-slate-700">
                <p><strong>Шаг 1.</strong> Шаблоны проверки и простые нормализаторы исправляют очевидные ошибки: почту, телефоны, даты и технические опечатки.</p>
                <p><strong>Шаг 2.</strong> После очистки явных проблем спорные случаи можно отправлять в DeepSeek как бесплатный слой ИИ.</p>
                <p><strong>Почему так:</strong> сначала понятные и прозрачные правила, потом языковая модель. Это дешевле, понятнее и лучше подходит под дипломную работу.</p>
            </div>
        </section>

        <section class="panel">
            <h3 class="soft-title">Состояние таблиц для шага с ИИ</h3>
            @if($datasets->isNotEmpty())
                <div class="mt-6 space-y-4">
                    @foreach($datasets as $dataset)
                        <x-mini-stat
                            :label="$dataset->name"
                            :value="data_get($dataset->metrics, 'deepseek_stage_ready', false) ? 'Можно отправлять в DeepSeek' : 'Сначала завершить проверку по шаблонам'"
                        />
                    @endforeach
                </div>
            @else
                <x-empty-state
                    class="mt-6 bg-transparent p-0 shadow-none"
                    title="Пока нечего отправлять"
                    description="Сначала загрузи хотя бы одну таблицу, чтобы подготовить данные к шагу с ИИ."
                />
            @endif
        </section>
    </div>
</x-layout>
