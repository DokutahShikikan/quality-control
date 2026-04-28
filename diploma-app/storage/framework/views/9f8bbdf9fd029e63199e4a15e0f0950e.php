<?php
    $statusLabels = [
        'open' => 'Открыта',
        'fixed' => 'Исправлена',
        'ignored' => 'Пропущена',
    ];
?>

<section class="panel">
    <?php if (isset($component)) { $__componentOriginal436399e29d00ce6b8f47e38277d39536 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal436399e29d00ce6b8f47e38277d39536 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-header','data' => ['title' => 'Последние ошибки']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Последние ошибки']); ?>
        <a href="/issues?dataset=<?php echo e($dataset->id); ?>" class="secondary-button">Все ошибки</a>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal436399e29d00ce6b8f47e38277d39536)): ?>
<?php $attributes = $__attributesOriginal436399e29d00ce6b8f47e38277d39536; ?>
<?php unset($__attributesOriginal436399e29d00ce6b8f47e38277d39536); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal436399e29d00ce6b8f47e38277d39536)): ?>
<?php $component = $__componentOriginal436399e29d00ce6b8f47e38277d39536; ?>
<?php unset($__componentOriginal436399e29d00ce6b8f47e38277d39536); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginalc8463834ba515134d5c98b88e1a9dc03 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc8463834ba515134d5c98b88e1a9dc03 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.data-table','data' => ['class' => 'mt-6','sticky' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('data-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-6','sticky' => true]); ?>
        <thead>
            <tr>
                <th>Колонка</th>
                <th>Что не так</th>
                <th>Было</th>
                <th>Предлагаем исправить</th>
                <th>Состояние</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $dataset->issues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $issue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($issue->column_name ?: 'Строка'); ?></td>
                    <td><?php echo e($issue->title); ?></td>
                    <td><?php echo e($issue->original_value ?: 'Пусто'); ?></td>
                    <td><?php echo e($issue->suggested_value ?: 'Нет безопасного варианта'); ?></td>
                    <td><?php echo e($statusLabels[$issue->status] ?? $issue->status); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="text-center text-slate-500">Ошибок пока нет.</td>
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
</section>
<?php /**PATH C:\Users\minho\Desktop\Курсча_преддиплом\diploma-app\resources\views/datasets/partials/recent-errors-table.blade.php ENDPATH**/ ?>