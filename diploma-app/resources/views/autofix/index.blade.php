<x-layout title="Автоисправление" current="autofix">
    <div class="space-y-8">
        <section class="panel">
            <x-section-header title="Как работает исправление" />
            <div class="mt-6 space-y-4 text-lg leading-8 text-slate-700">
                <p><strong>Шаг 1.</strong> Шаблоны проверки исправляют очевидные ошибки: даты, статусы, телефоны и простые опечатки.</p>
                <p><strong>Шаг 2.</strong> Оставшиеся спорные ошибки можно отправить в DeepSeek. Сервис попробует исправить их автоматически и сразу заново проверит таблицу.</p>
                <p><strong>Важно.</strong> ИИ запускается только для таблиц, где уже не осталось понятных автоматических исправлений по шаблонам.</p>
            </div>
        </section>

        <section class="panel">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="soft-title">Готовность DeepSeek</h3>
                    <p class="mt-2 text-sm text-slate-500">Запускай ИИ только после обычной проверки, когда в таблице остались спорные случаи.</p>
                </div>

                <x-status-pill :tone="$isDeepSeekConfigured ? 'clean' : 'review'">
                    {{ $isDeepSeekConfigured ? 'DeepSeek подключён' : 'Нужно указать API-ключ' }}
                </x-status-pill>
            </div>

            @if($datasets->isNotEmpty())
                <div class="mt-6 grid gap-5 xl:grid-cols-2">
                    @foreach($datasets as $dataset)
                        @php
                            $canRun = $isDeepSeekConfigured
                                && $dataset->deepseek_enabled
                                && data_get($dataset->metrics, 'deepseek_stage_ready', false);
                        @endphp

                        <section class="flex h-full flex-col rounded-[24px] border border-slate-200 bg-slate-50/70 p-5">
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                <div class="min-w-0">
                                    <h4 class="text-xl font-black text-slate-900">{{ $dataset->name }}</h4>
                                    <p class="mt-2 text-sm text-slate-500">{{ $dataset->source_filename }}</p>
                                </div>

                                <x-status-pill :tone="$canRun ? 'clean' : 'review'">
                                    {{ $canRun ? 'Можно запускать ИИ' : 'Пока рано' }}
                                </x-status-pill>
                            </div>

                            <div class="mt-5 grid gap-3 sm:grid-cols-2">
                                <x-mini-stat label="Открытых ошибок" :value="data_get($dataset->metrics, 'open_issues', 0)" />
                                <x-mini-stat label="Исправлений по шаблонам осталось" :value="data_get($dataset->metrics, 'fixable_issues', 0)" />
                            </div>

                            <p class="mt-5 text-sm leading-7 text-slate-600">
                                @if(! $dataset->deepseek_enabled)
                                    Для этой таблицы шаг с ИИ не включён при загрузке.
                                @elseif(! $isDeepSeekConfigured)
                                    В `.env` ещё не настроен ключ DeepSeek API.
                                @elseif(data_get($dataset->metrics, 'fixable_issues', 0) > 0)
                                    Сначала закончи понятные исправления по шаблонам, потом запускай ИИ.
                                @elseif(data_get($dataset->metrics, 'open_issues', 0) === 0)
                                    Для этой таблицы сейчас нет открытых ошибок для обработки ИИ.
                                @else
                                    ИИ попробует исправить спорные значения и затем сразу запустит новую проверку таблицы.
                                @endif
                            </p>

                            <div class="mt-auto flex flex-wrap gap-3 pt-5">
                                <form method="POST" action="/autofix/{{ $dataset->id }}">
                                    @csrf
                                    <button class="primary-button" type="submit" @disabled(! $canRun)>Запустить исправление через ИИ</button>
                                </form>

                                <a href="/datasets/{{ $dataset->id }}" class="secondary-button">Открыть таблицу</a>
                            </div>
                        </section>
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
