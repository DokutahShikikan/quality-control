<x-layout title="Автоисправление" current="autofix">
    <div class="grid gap-8 xl:grid-cols-[1fr_1fr]">
        <section class="panel">
            <h2 class="panel-title">Двухэтапная стратегия исправления</h2>
            <div class="mt-6 space-y-4 text-lg leading-8 text-slate-700">
                <p><strong>Этап 1.</strong> Regex и нормализаторы исправляют очевидные ошибки: email, телефоны, даты и технические опечатки.</p>
                <p><strong>Этап 2.</strong> После очистки явных проблем спорные случаи можно отправлять в DeepSeek API как бесплатный AI-слой.</p>
                <p><strong>Почему так:</strong> сначала детерминированные правила, потом LLM. Это дешевле, прозрачнее и лучше подходит под диплом.</p>
            </div>
        </section>

        <section class="panel">
            <h3 class="soft-title">Статус наборов для AI-этапа</h3>
            <div class="mt-6 space-y-4">
                @forelse($datasets as $dataset)
                    <div class="mini-stat">
                        <span>{{ $dataset->name }}</span>
                        <strong>{{ data_get($dataset->metrics, 'deepseek_stage_ready', false) ? 'Можно отправлять в DeepSeek' : 'Сначала завершить regex-разбор' }}</strong>
                    </div>
                @empty
                    <p class="text-lg text-slate-600">Сначала загрузи хотя бы один набор.</p>
                @endforelse
            </div>
        </section>
    </div>
</x-layout>
