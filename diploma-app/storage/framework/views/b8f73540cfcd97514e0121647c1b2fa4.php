<?php if (isset($component)) { $__componentOriginal23a33f287873b564aaf305a1526eada4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal23a33f287873b564aaf305a1526eada4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout','data' => ['title' => 'Повторы','current' => 'duplicates']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Повторы','current' => 'duplicates']); ?>
    <?php
        $statusLabels = [
            'open' => 'Открыт',
            'fixed' => 'Исправлен',
            'ignored' => 'Пропущен',
        ];
    ?>

    <div class="space-y-6">
        <?php if (isset($component)) { $__componentOriginal636503f3f14d65b77299d1b3e99577f2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal636503f3f14d65b77299d1b3e99577f2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.conflicts-tabs','data' => ['current' => 'duplicates','datasetId' => $selectedDataset?->id]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('conflicts-tabs'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['current' => 'duplicates','dataset-id' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($selectedDataset?->id)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal636503f3f14d65b77299d1b3e99577f2)): ?>
<?php $attributes = $__attributesOriginal636503f3f14d65b77299d1b3e99577f2; ?>
<?php unset($__attributesOriginal636503f3f14d65b77299d1b3e99577f2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal636503f3f14d65b77299d1b3e99577f2)): ?>
<?php $component = $__componentOriginal636503f3f14d65b77299d1b3e99577f2; ?>
<?php unset($__componentOriginal636503f3f14d65b77299d1b3e99577f2); ?>
<?php endif; ?>

        <div class="panel">
            <form method="GET" action="/duplicates" class="space-y-4">
                <?php if($selectedDataset): ?>
                    <input type="hidden" name="dataset" value="<?php echo e($selectedDataset->id); ?>">
                    <p class="text-sm text-slate-500">
                        Показаны повторы только для таблицы <span class="font-semibold text-slate-700"><?php echo e($selectedDataset->name); ?></span>.
                    </p>
                <?php endif; ?>

                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    <?php if (isset($component)) { $__componentOriginalcfc16483a1e4ddb0071f3793d67ad40c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcfc16483a1e4ddb0071f3793d67ad40c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.input-field','data' => ['class' => 'md:col-span-2 xl:col-span-1','name' => 'q','label' => 'Поиск','value' => $filters['q'] ?? '','placeholder' => 'Таблица или причина совпадения']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.input-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'md:col-span-2 xl:col-span-1','name' => 'q','label' => 'Поиск','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($filters['q'] ?? ''),'placeholder' => 'Таблица или причина совпадения']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcfc16483a1e4ddb0071f3793d67ad40c)): ?>
<?php $attributes = $__attributesOriginalcfc16483a1e4ddb0071f3793d67ad40c; ?>
<?php unset($__attributesOriginalcfc16483a1e4ddb0071f3793d67ad40c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcfc16483a1e4ddb0071f3793d67ad40c)): ?>
<?php $component = $__componentOriginalcfc16483a1e4ddb0071f3793d67ad40c; ?>
<?php unset($__componentOriginalcfc16483a1e4ddb0071f3793d67ad40c); ?>
<?php endif; ?>

                    <label class="form-field">
                        <span class="form-label">Состояние</span>
                        <select name="status" class="text-field">
                            <option value="">Все</option>
                            <?php $__currentLoopData = $statusLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($value); ?>" <?php if(($filters['status'] ?? '') === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </label>

                    <label class="form-field">
                        <span class="form-label">Сортировка</span>
                        <select name="sort" class="text-field">
                            <option value="newest" <?php if(($filters['sort'] ?? '') === 'newest'): echo 'selected'; endif; ?>>Сначала новые</option>
                            <option value="oldest" <?php if(($filters['sort'] ?? '') === 'oldest'): echo 'selected'; endif; ?>>Сначала старые</option>
                            <option value="confidence_high" <?php if(($filters['sort'] ?? '') === 'confidence_high'): echo 'selected'; endif; ?>>С высокой уверенностью</option>
                            <option value="confidence_low" <?php if(($filters['sort'] ?? '') === 'confidence_low'): echo 'selected'; endif; ?>>С низкой уверенностью</option>
                        </select>
                    </label>
                </div>

                <?php if (isset($component)) { $__componentOriginal98d29adaa4406bec3dfa33b112d9a220 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal98d29adaa4406bec3dfa33b112d9a220 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form-actions','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form-actions'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                    <button type="submit" class="primary-button">Применить</button>
                    <a href="<?php echo e($selectedDataset ? '/duplicates?dataset='.$selectedDataset->id : '/duplicates'); ?>" class="secondary-button">Сбросить</a>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal98d29adaa4406bec3dfa33b112d9a220)): ?>
<?php $attributes = $__attributesOriginal98d29adaa4406bec3dfa33b112d9a220; ?>
<?php unset($__attributesOriginal98d29adaa4406bec3dfa33b112d9a220); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal98d29adaa4406bec3dfa33b112d9a220)): ?>
<?php $component = $__componentOriginal98d29adaa4406bec3dfa33b112d9a220; ?>
<?php unset($__componentOriginal98d29adaa4406bec3dfa33b112d9a220); ?>
<?php endif; ?>
            </form>
        </div>

        <div class="panel">
            <?php if($duplicates->total() > 0): ?>
                <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm font-semibold uppercase tracking-[0.14em] text-slate-500">
                        Страница <?php echo e($duplicates->currentPage()); ?> из <?php echo e($duplicates->lastPage()); ?>

                    </p>
                    <p class="text-sm text-slate-500">
                        Показано <?php echo e($duplicates->firstItem()); ?>-<?php echo e($duplicates->lastItem()); ?> из <?php echo e($duplicates->total()); ?>

                    </p>
                </div>
            <?php endif; ?>

            <?php if (isset($component)) { $__componentOriginalc8463834ba515134d5c98b88e1a9dc03 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc8463834ba515134d5c98b88e1a9dc03 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.data-table','data' => ['sticky' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('data-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['sticky' => true]); ?>
                <thead>
                    <tr>
                        <th>Таблица</th>
                        <th>Основная строка</th>
                        <th>Повторяющаяся строка</th>
                        <th>Уверенность</th>
                        <th>Почему это повтор</th>
                        <th>Состояние</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $duplicates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $duplicate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><a href="/datasets/<?php echo e($duplicate->dataset_id); ?>" class="link link-hover"><?php echo e($duplicate->dataset->name); ?></a></td>
                            <td>#<?php echo e($duplicate->primaryRow->row_index); ?></td>
                            <td>#<?php echo e($duplicate->duplicateRow->row_index); ?></td>
                            <td><?php echo e(number_format($duplicate->confidence * 100, 0)); ?>%</td>
                            <td><?php echo e($duplicate->rationale); ?></td>
                            <td><?php echo e($statusLabels[$duplicate->status] ?? $duplicate->status); ?></td>
                            <td>
                                <div class="table-actions duplicate-actions">
                                    <form method="POST" action="/duplicates/<?php echo e($duplicate->id); ?>/fix" class="table-action-form">
                                        <?php echo csrf_field(); ?>
                                        <button class="table-primary-button action-button" type="submit" <?php echo e($duplicate->status !== 'open' ? 'disabled' : ''); ?>>Удалить повтор</button>
                                    </form>
                                    <form method="POST" action="/duplicates/<?php echo e($duplicate->id); ?>/fix-group" class="table-action-form">
                                        <?php echo csrf_field(); ?>
                                        <button class="table-secondary-button action-button" type="submit" <?php echo e($duplicate->status !== 'open' ? 'disabled' : ''); ?>>Удалить все повторы</button>
                                    </form>
                                    <form method="POST" action="/duplicates/<?php echo e($duplicate->id); ?>/ignore" class="table-action-form">
                                        <?php echo csrf_field(); ?>
                                        <button class="table-secondary-button action-button" type="submit" <?php echo e($duplicate->status !== 'open' ? 'disabled' : ''); ?>>Игнорировать</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center text-slate-500">Повторы не найдены.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc8463834ba515134d5c98b88e1a9dc03)): ?>
<?php $attributes = $__attributesOriginalc8463834ba515134d5c98b88e1a9dc03; ?>
<?php unset($__attributesOriginalc8463834ba515134d5c98b88e1a9dc03); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc8463834ba515134d5c98b88e1a9dc03)): ?>
<?php $component = $__componentOriginalc8463834ba515134d5c98b88e1a9dc03; ?>
<?php unset($__componentOriginalc8463834ba515134d5c98b88e1a9dc03); ?>
<?php endif; ?>

            <?php if($duplicates->total() > 0): ?>
                <div class="mt-6">
                    <?php echo e($duplicates->onEachSide(1)->links()); ?>

                </div>
            <?php endif; ?>
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
<?php /**PATH C:\Users\minho\Desktop\Курсча_преддиплом\diploma-app\resources\views/duplicates/index.blade.php ENDPATH**/ ?>