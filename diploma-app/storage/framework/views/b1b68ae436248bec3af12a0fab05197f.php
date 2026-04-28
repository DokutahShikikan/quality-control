<div class="panel">
    <?php if($issues->total() > 0): ?>
        <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm font-semibold uppercase tracking-[0.14em] text-slate-500">
                Страница <?php echo e($issues->currentPage()); ?> из <?php echo e($issues->lastPage()); ?>

            </p>
            <p class="text-sm text-slate-500">
                Показано <?php echo e($issues->firstItem()); ?>-<?php echo e($issues->lastItem()); ?> из <?php echo e($issues->total()); ?>

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
                <th>Строка</th>
                <th>Колонка</th>
                <th>Что не так</th>
                <th>Было</th>
                <th>Предлагаем исправить</th>
                <th>Состояние</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $issues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $issue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><a href="/datasets/<?php echo e($issue->dataset_id); ?>" class="link link-hover"><?php echo e($issue->dataset->name); ?></a></td>
                    <td>#<?php echo e(data_get($issue->meta, 'row_index', '-')); ?></td>
                    <td><?php echo e($issue->column_name ?: 'Строка'); ?></td>
                    <td><?php echo e($issue->title); ?></td>
                    <td><?php echo e($issue->original_value ?: 'Пусто'); ?></td>
                    <td><?php echo e($issue->suggested_value ?: 'Нет'); ?></td>
                    <td><?php echo e($statusLabels[$issue->status] ?? $issue->status); ?></td>
                    <td class="align-top">
                        <div class="table-actions issue-actions">
                            <form method="POST" action="/issues/<?php echo e($issue->id); ?>/fix" class="table-action-form" data-issues-action-form>
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="dataset" value="<?php echo e($filters['dataset'] ?? ''); ?>">
                                <input type="hidden" name="dataset_id" value="<?php echo e($issue->dataset_id); ?>">
                                <input type="hidden" name="dataset_row_id" value="<?php echo e($issue->dataset_row_id); ?>">
                                <input type="hidden" name="column_name" value="<?php echo e($issue->column_name); ?>">
                                <input type="hidden" name="suggested_value" value="<?php echo e($issue->suggested_value); ?>">
                                <button
                                    class="table-primary-button action-button"
                                    type="submit"
                                    <?php echo e($issue->status !== 'open' || ! $issue->suggested_value ? 'disabled' : ''); ?>

                                >Исправить</button>
                            </form>
                            <form method="POST" action="/issues/<?php echo e($issue->id); ?>/fix-similar" class="table-action-form" data-issues-action-form>
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="dataset" value="<?php echo e($filters['dataset'] ?? ''); ?>">
                                <button class="table-secondary-button action-button min-h-14 px-3 py-3" type="submit" <?php echo e($issue->status !== 'open' || ! $issue->suggested_value ? 'disabled' : ''); ?>>Исправить все подобные ошибки</button>
                            </form>
                            <form method="POST" action="/issues/<?php echo e($issue->id); ?>/ignore" class="table-action-form" data-issues-action-form>
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="dataset" value="<?php echo e($filters['dataset'] ?? ''); ?>">
                                <button class="table-secondary-button action-button" type="submit" <?php echo e($issue->status !== 'open' ? 'disabled' : ''); ?>>Пропустить</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="text-center text-slate-500">Ошибки не найдены.</td>
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

    <?php if($issues->total() > 0): ?>
        <div class="mt-6">
            <?php echo e($issues->onEachSide(1)->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\Users\minho\Desktop\Курсча_преддиплом\diploma-app\resources\views/issues/partials/table-panel.blade.php ENDPATH**/ ?>