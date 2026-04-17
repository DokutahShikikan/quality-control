<x-layout title="Автоисправление" current="autofix">
    <div class="grid gap-8 xl:grid-cols-[1fr_1fr]">
        <section class="panel">
            <x-section-header title="Двухэтапная стратегия исправления" />
            <div class="mt-6 space-y-4 text-lg leading-8 text-slate-700">
                <p><strong>Этап 1.</strong> Regex и нормализаторы исправляют очевидные ошибки: email, телефоны, даты и технические опечатки.</p>
                <p><strong>Этап 2.</strong> После очистки явных проблем спорные случаи можно отправлять в DeepSeek API как бесплатный AI-слой.</p>
                <p><strong>Почему так:</strong> сначала детерминированные правила, потом LLM. Это дешевле, прозрачнее и лучше подходит под диплом.</p>
            </div>
        </section>

        <section class="panel">
            <h3 class="soft-title">Статус наборов для AI-этапа</h3>
            @if($datasets->isNotEmpty())
                <div class="mt-6 space-y-4">
                    @foreach($datasets as $dataset)
                        <x-mini-stat
                            :label="$dataset->name"
                            :value="data_get($dataset->metrics, 'deepseek_stage_ready', false) ? 'Можно отправлять в DeepSeek' : 'Сначала завершить regex-разбор'"
                        />
                    @endforeach
                </div>
            @else
                <x-empty-state
                    class="mt-6 bg-transparent p-0 shadow-none"
                    title="Пока нечего отправлять"
                    description="Сначала загрузи хотя бы один набор, чтобы подготовить данные к AI-этапу."
                />
            @endif
        </section>
    </div>
</x-layout>
