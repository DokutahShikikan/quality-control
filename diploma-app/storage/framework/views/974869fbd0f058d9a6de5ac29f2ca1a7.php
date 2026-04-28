<?php if (isset($component)) { $__componentOriginal23a33f287873b564aaf305a1526eada4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal23a33f287873b564aaf305a1526eada4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout','data' => ['title' => 'Ошибки в данных','current' => 'issues']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Ошибки в данных','current' => 'issues']); ?>
    <div class="space-y-6">
        <?php if (isset($component)) { $__componentOriginal636503f3f14d65b77299d1b3e99577f2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal636503f3f14d65b77299d1b3e99577f2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.conflicts-tabs','data' => ['current' => 'issues','datasetId' => $selectedDataset?->id]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('conflicts-tabs'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['current' => 'issues','dataset-id' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($selectedDataset?->id)]); ?>
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
            <form method="GET" action="/issues" class="space-y-4">
                <?php if($selectedDataset): ?>
                    <input type="hidden" name="dataset" value="<?php echo e($selectedDataset->id); ?>">
                    <p class="text-sm text-slate-500">
                        Показаны ошибки только для таблицы <span class="font-semibold text-slate-700"><?php echo e($selectedDataset->name); ?></span>.
                    </p>
                <?php endif; ?>

                <div class="grid gap-4 md:grid-cols-2 2xl:grid-cols-5">
                    <?php if (isset($component)) { $__componentOriginalcfc16483a1e4ddb0071f3793d67ad40c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcfc16483a1e4ddb0071f3793d67ad40c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.input-field','data' => ['class' => 'md:col-span-2 2xl:col-span-1','name' => 'q','label' => 'Поиск','value' => $filters['q'] ?? '','placeholder' => 'Таблица, колонка, значение или текст ошибки']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.input-field'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'md:col-span-2 2xl:col-span-1','name' => 'q','label' => 'Поиск','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($filters['q'] ?? ''),'placeholder' => 'Таблица, колонка, значение или текст ошибки']); ?>
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
                        <span class="form-label">Вид ошибки</span>
                        <select name="issue_type" class="text-field">
                            <option value="">Все</option>
                            <?php $__currentLoopData = $issueTypeLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($value); ?>" <?php if(($filters['issue_type'] ?? '') === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </label>

                    <label class="form-field">
                        <span class="form-label">Важность</span>
                        <select name="severity" class="text-field">
                            <option value="">Все</option>
                            <?php $__currentLoopData = $severityLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($value); ?>" <?php if(($filters['severity'] ?? '') === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </label>

                    <label class="form-field">
                        <span class="form-label">Сортировка</span>
                        <select name="sort" class="text-field">
                            <option value="newest" <?php if(($filters['sort'] ?? '') === 'newest'): echo 'selected'; endif; ?>>Сначала новые</option>
                            <option value="oldest" <?php if(($filters['sort'] ?? '') === 'oldest'): echo 'selected'; endif; ?>>Сначала старые</option>
                            <option value="severity" <?php if(($filters['sort'] ?? '') === 'severity'): echo 'selected'; endif; ?>>По важности</option>
                            <option value="status" <?php if(($filters['sort'] ?? '') === 'status'): echo 'selected'; endif; ?>>По состоянию</option>
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
                    <a href="<?php echo e($selectedDataset ? '/issues?dataset='.$selectedDataset->id : '/issues'); ?>" class="secondary-button">Сбросить</a>
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

        <div
            class="relative"
            data-issues-table-root
            data-refresh-url="/issues/table"
        >
            <div class="hidden mb-6 rounded-[22px] px-5 py-4 text-sm font-semibold" data-issues-feedback></div>
            <div class="issues-loading hidden" data-issues-loading>
                <div class="issues-loading-card">
                    <div class="issues-loading-dots" aria-hidden="true">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                    <div class="issues-loading-copy">
                        <strong>Проверяем изменения</strong>
                        <span>Список ошибок обновляется без перезагрузки страницы</span>
                    </div>
                </div>
            </div>

            <?php echo $__env->make('issues.partials.table-panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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
<?php /**PATH C:\Users\minho\Desktop\Курсча_преддиплом\diploma-app\resources\views/issues/index.blade.php ENDPATH**/ ?>