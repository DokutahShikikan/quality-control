<section class="panel">
    <?php if (isset($component)) { $__componentOriginal436399e29d00ce6b8f47e38277d39536 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal436399e29d00ce6b8f47e38277d39536 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-header','data' => ['title' => 'Последние повторы']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Последние повторы']); ?>
        <a href="/duplicates?dataset=<?php echo e($dataset->id); ?>" class="secondary-button">Все повторы</a>
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
                <th>Основная строка</th>
                <th>Повторяющаяся строка</th>
                <th>Уверенность</th>
                <th>Почему решили, что это повтор</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $dataset->duplicateCandidates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $duplicate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>#<?php echo e($duplicate->primaryRow->row_index); ?></td>
                    <td>#<?php echo e($duplicate->duplicateRow->row_index); ?></td>
                    <td><?php echo e(number_format($duplicate->confidence * 100, 0)); ?>%</td>
                    <td><?php echo e($duplicate->rationale); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="4" class="text-center text-slate-500">Повторы не найдены.</td>
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
<?php /**PATH C:\Users\minho\Desktop\Курсча_преддиплом\diploma-app\resources\views/datasets/partials/recent-duplicates-table.blade.php ENDPATH**/ ?>