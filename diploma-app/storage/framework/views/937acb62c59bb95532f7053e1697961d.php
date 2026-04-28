<?php if (isset($component)) { $__componentOriginal23a33f287873b564aaf305a1526eada4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal23a33f287873b564aaf305a1526eada4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout','data' => ['title' => $dataset->name,'current' => 'datasets']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($dataset->name),'current' => 'datasets']); ?>
    <?php
        $importLabel = match ($dataset->import_status) {
            'queued' => 'Файл ждёт своей очереди на загрузку.',
            'processing' => 'Файл сейчас загружается и проверяется.',
            'failed' => 'Во время загрузки произошла ошибка.',
            default => 'Файл загружен.',
        };

        $importStateLabel = match ($dataset->import_status) {
            'queued' => 'В очереди',
            'processing' => 'Загружается',
            'failed' => 'Ошибка загрузки',
            'ready' => 'Готово',
            default => $dataset->import_status,
        };
    ?>

    <div
        class="space-y-8"
        data-live-panels
        data-refresh-url="/datasets/<?php echo e($dataset->id); ?>/live-panels"
        data-refresh-interval="5000"
    >
        <?php if($dataset->import_status !== 'ready'): ?>
            <section class="panel">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="space-y-2">
                        <h2 class="panel-title">Состояние загрузки</h2>
                        <p class="text-base text-slate-700"><?php echo e($importLabel); ?></p>
                        <?php if($dataset->import_error): ?>
                            <p class="text-sm text-rose-600"><?php echo e($dataset->import_error); ?></p>
                        <?php endif; ?>
                    </div>

                    <?php if (isset($component)) { $__componentOriginal0f0f6d48f1e3fcafba02703e0b070890 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0f0f6d48f1e3fcafba02703e0b070890 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status-pill','data' => ['tone' => 'review']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('status-pill'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => 'review']); ?>
                        <?php echo e($importStateLabel); ?>

                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0f0f6d48f1e3fcafba02703e0b070890)): ?>
<?php $attributes = $__attributesOriginal0f0f6d48f1e3fcafba02703e0b070890; ?>
<?php unset($__attributesOriginal0f0f6d48f1e3fcafba02703e0b070890); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0f0f6d48f1e3fcafba02703e0b070890)): ?>
<?php $component = $__componentOriginal0f0f6d48f1e3fcafba02703e0b070890; ?>
<?php unset($__componentOriginal0f0f6d48f1e3fcafba02703e0b070890); ?>
<?php endif; ?>
                </div>
            </section>
        <?php endif; ?>

        <div class="metric-grid">
            <?php if (isset($component)) { $__componentOriginal6d74059c34730cb2c742dae13948a701 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6d74059c34730cb2c742dae13948a701 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.metric-card','data' => ['label' => 'Строк','value' => $dataset->total_rows]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('metric-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Строк','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($dataset->total_rows)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6d74059c34730cb2c742dae13948a701)): ?>
<?php $attributes = $__attributesOriginal6d74059c34730cb2c742dae13948a701; ?>
<?php unset($__attributesOriginal6d74059c34730cb2c742dae13948a701); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6d74059c34730cb2c742dae13948a701)): ?>
<?php $component = $__componentOriginal6d74059c34730cb2c742dae13948a701; ?>
<?php unset($__componentOriginal6d74059c34730cb2c742dae13948a701); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal6d74059c34730cb2c742dae13948a701 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6d74059c34730cb2c742dae13948a701 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.metric-card','data' => ['label' => 'Найдено ошибок','value' => data_get($dataset->metrics, 'open_issues', 0)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('metric-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Найдено ошибок','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(data_get($dataset->metrics, 'open_issues', 0))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6d74059c34730cb2c742dae13948a701)): ?>
<?php $attributes = $__attributesOriginal6d74059c34730cb2c742dae13948a701; ?>
<?php unset($__attributesOriginal6d74059c34730cb2c742dae13948a701); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6d74059c34730cb2c742dae13948a701)): ?>
<?php $component = $__componentOriginal6d74059c34730cb2c742dae13948a701; ?>
<?php unset($__componentOriginal6d74059c34730cb2c742dae13948a701); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal6d74059c34730cb2c742dae13948a701 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6d74059c34730cb2c742dae13948a701 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.metric-card','data' => ['label' => 'Найдено повторов','value' => data_get($dataset->metrics, 'open_duplicates', 0)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('metric-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Найдено повторов','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(data_get($dataset->metrics, 'open_duplicates', 0))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6d74059c34730cb2c742dae13948a701)): ?>
<?php $attributes = $__attributesOriginal6d74059c34730cb2c742dae13948a701; ?>
<?php unset($__attributesOriginal6d74059c34730cb2c742dae13948a701); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6d74059c34730cb2c742dae13948a701)): ?>
<?php $component = $__componentOriginal6d74059c34730cb2c742dae13948a701; ?>
<?php unset($__componentOriginal6d74059c34730cb2c742dae13948a701); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal6d74059c34730cb2c742dae13948a701 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6d74059c34730cb2c742dae13948a701 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.metric-card','data' => ['label' => 'Заполнено данных','value' => data_get($dataset->metrics, 'completeness_rate', 0).'%']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('metric-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Заполнено данных','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(data_get($dataset->metrics, 'completeness_rate', 0).'%')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6d74059c34730cb2c742dae13948a701)): ?>
<?php $attributes = $__attributesOriginal6d74059c34730cb2c742dae13948a701; ?>
<?php unset($__attributesOriginal6d74059c34730cb2c742dae13948a701); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6d74059c34730cb2c742dae13948a701)): ?>
<?php $component = $__componentOriginal6d74059c34730cb2c742dae13948a701; ?>
<?php unset($__componentOriginal6d74059c34730cb2c742dae13948a701); ?>
<?php endif; ?>
        </div>

        <div class="grid gap-8 xl:grid-cols-[1.15fr_0.85fr]">
            <section class="panel">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <h2 class="panel-title">О таблице</h2>
                        <p class="mt-4 text-lg leading-8 text-slate-700">
                            <?php echo e($dataset->description ?: 'Описание не добавлено. Таблица загружена для поиска пустых ячеек, неверных форматов и повторов.'); ?>

                        </p>
                    </div>

                    <?php if (isset($component)) { $__componentOriginal0f0f6d48f1e3fcafba02703e0b070890 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0f0f6d48f1e3fcafba02703e0b070890 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status-pill','data' => ['tone' => $dataset->import_status === 'ready' && $dataset->review_status === 'clean' ? 'clean' : 'review']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('status-pill'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($dataset->import_status === 'ready' && $dataset->review_status === 'clean' ? 'clean' : 'review')]); ?>
                        <?php echo e($dataset->import_status === 'ready' ? ($dataset->review_status === 'clean' ? 'Проблем не найдено' : 'Нужно проверить') : $importStateLabel); ?>

                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0f0f6d48f1e3fcafba02703e0b070890)): ?>
<?php $attributes = $__attributesOriginal0f0f6d48f1e3fcafba02703e0b070890; ?>
<?php unset($__attributesOriginal0f0f6d48f1e3fcafba02703e0b070890); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0f0f6d48f1e3fcafba02703e0b070890)): ?>
<?php $component = $__componentOriginal0f0f6d48f1e3fcafba02703e0b070890; ?>
<?php unset($__componentOriginal0f0f6d48f1e3fcafba02703e0b070890); ?>
<?php endif; ?>
                </div>

                <dl class="mt-8 grid gap-4 md:grid-cols-2">
                    <?php if (isset($component)) { $__componentOriginal6cc5af0eaa762b49614e67b4d5702657 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6cc5af0eaa762b49614e67b4d5702657 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.mini-stat','data' => ['label' => 'Исходный файл','value' => $dataset->source_filename]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('mini-stat'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Исходный файл','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($dataset->source_filename)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6cc5af0eaa762b49614e67b4d5702657)): ?>
<?php $attributes = $__attributesOriginal6cc5af0eaa762b49614e67b4d5702657; ?>
<?php unset($__attributesOriginal6cc5af0eaa762b49614e67b4d5702657); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6cc5af0eaa762b49614e67b4d5702657)): ?>
<?php $component = $__componentOriginal6cc5af0eaa762b49614e67b4d5702657; ?>
<?php unset($__componentOriginal6cc5af0eaa762b49614e67b4d5702657); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal6cc5af0eaa762b49614e67b4d5702657 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6cc5af0eaa762b49614e67b4d5702657 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.mini-stat','data' => ['label' => 'Состояние загрузки','value' => $importStateLabel]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('mini-stat'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Состояние загрузки','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($importStateLabel)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6cc5af0eaa762b49614e67b4d5702657)): ?>
<?php $attributes = $__attributesOriginal6cc5af0eaa762b49614e67b4d5702657; ?>
<?php unset($__attributesOriginal6cc5af0eaa762b49614e67b4d5702657); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6cc5af0eaa762b49614e67b4d5702657)): ?>
<?php $component = $__componentOriginal6cc5af0eaa762b49614e67b4d5702657; ?>
<?php unset($__componentOriginal6cc5af0eaa762b49614e67b4d5702657); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal6cc5af0eaa762b49614e67b4d5702657 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6cc5af0eaa762b49614e67b4d5702657 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.mini-stat','data' => ['label' => 'Последняя проверка','value' => optional($dataset->last_checked_at)->format('d.m.Y H:i') ?: 'Ещё не запускалась']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('mini-stat'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Последняя проверка','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(optional($dataset->last_checked_at)->format('d.m.Y H:i') ?: 'Ещё не запускалась')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6cc5af0eaa762b49614e67b4d5702657)): ?>
<?php $attributes = $__attributesOriginal6cc5af0eaa762b49614e67b4d5702657; ?>
<?php unset($__attributesOriginal6cc5af0eaa762b49614e67b4d5702657); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6cc5af0eaa762b49614e67b4d5702657)): ?>
<?php $component = $__componentOriginal6cc5af0eaa762b49614e67b4d5702657; ?>
<?php unset($__componentOriginal6cc5af0eaa762b49614e67b4d5702657); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal6cc5af0eaa762b49614e67b4d5702657 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6cc5af0eaa762b49614e67b4d5702657 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.mini-stat','data' => ['label' => 'Ошибок по формату','value' => data_get($dataset->metrics, 'format_error_rate', 0).'%']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('mini-stat'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Ошибок по формату','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(data_get($dataset->metrics, 'format_error_rate', 0).'%')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6cc5af0eaa762b49614e67b4d5702657)): ?>
<?php $attributes = $__attributesOriginal6cc5af0eaa762b49614e67b4d5702657; ?>
<?php unset($__attributesOriginal6cc5af0eaa762b49614e67b4d5702657); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6cc5af0eaa762b49614e67b4d5702657)): ?>
<?php $component = $__componentOriginal6cc5af0eaa762b49614e67b4d5702657; ?>
<?php unset($__componentOriginal6cc5af0eaa762b49614e67b4d5702657); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal6cc5af0eaa762b49614e67b4d5702657 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6cc5af0eaa762b49614e67b4d5702657 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.mini-stat','data' => ['label' => 'Следующий шаг','value' => data_get($dataset->metrics, 'deepseek_stage_ready', false) ? 'Можно включать ИИ' : 'Сначала исправить понятные ошибки']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('mini-stat'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Следующий шаг','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(data_get($dataset->metrics, 'deepseek_stage_ready', false) ? 'Можно включать ИИ' : 'Сначала исправить понятные ошибки')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6cc5af0eaa762b49614e67b4d5702657)): ?>
<?php $attributes = $__attributesOriginal6cc5af0eaa762b49614e67b4d5702657; ?>
<?php unset($__attributesOriginal6cc5af0eaa762b49614e67b4d5702657); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6cc5af0eaa762b49614e67b4d5702657)): ?>
<?php $component = $__componentOriginal6cc5af0eaa762b49614e67b4d5702657; ?>
<?php unset($__componentOriginal6cc5af0eaa762b49614e67b4d5702657); ?>
<?php endif; ?>
                </dl>

                <div class="mt-8 flex flex-wrap gap-4">
                    <?php if($dataset->import_status === 'ready'): ?>
                        <form method="POST" action="/datasets/<?php echo e($dataset->id); ?>/analyze">
                            <?php echo csrf_field(); ?>
                            <button class="primary-button" type="submit">Запустить проверку заново</button>
                        </form>
                    <?php endif; ?>
                    <a href="/issues?dataset=<?php echo e($dataset->id); ?>" class="secondary-button">Открыть ошибки</a>
                    <a href="/duplicates?dataset=<?php echo e($dataset->id); ?>" class="secondary-button">Открыть повторы</a>
                    <a href="/datasets/<?php echo e($dataset->id); ?>/export" class="secondary-button">Скачать переработанный файл</a>
                    <form method="POST" action="/datasets/<?php echo e($dataset->id); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button class="danger-button" type="submit">Удалить таблицу</button>
                    </form>
                </div>
            </section>

            <div data-live-target="statsHtml">
                <?php echo $__env->make('datasets.partials.dataset-status-card', ['dataset' => $dataset, 'latestRun' => $latestRun], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>

        <div data-live-target="issuesHtml">
            <?php echo $__env->make('datasets.partials.recent-errors-table', ['dataset' => $dataset], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        <div data-live-target="duplicatesHtml">
            <?php echo $__env->make('datasets.partials.recent-duplicates-table', ['dataset' => $dataset], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal23a33f287873b564aaf305a1526eada4)): ?>
<?php $attributes = $__attributesOriginal23a33f287873b564aaf305a1526eada4; ?>
<?php unset($__attributesOriginal23a33f287873b564aaf305a1526eada4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal23a33f287873b564aaf305a1526eada4)): ?>
<?php $component = $__componentOriginal23a33f287873b564aaf305a1526eada4; ?>
<?php unset($__componentOriginal23a33f287873b564aaf305a1526eada4); ?>
<?php endif; ?>
<?php /**PATH C:\Users\minho\Desktop\Курсча_преддиплом\diploma-app\resources\views/datasets/show.blade.php ENDPATH**/ ?>