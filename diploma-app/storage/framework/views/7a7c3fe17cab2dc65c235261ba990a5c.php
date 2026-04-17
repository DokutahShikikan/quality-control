<?php if (isset($component)) { $__componentOriginal23a33f287873b564aaf305a1526eada4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal23a33f287873b564aaf305a1526eada4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout','data' => ['title' => 'Regex-правила','current' => 'rules']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Regex-правила','current' => 'rules']); ?>
    <div class="space-y-8">
        <section class="panel">
            <h2 class="panel-title">Активные правила качества</h2>
            <p class="mt-4 text-lg leading-8 text-slate-700">
                На первом этапе сайт опирается на regex и предсказуемые нормализаторы. Это безопасный слой,
                который исправляет очевидные ошибки до передачи оставшихся спорных случаев в DeepSeek API.
            </p>
        </section>

        <div class="overflow-x-auto panel">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Правило</th>
                        <th>Тип проблемы</th>
                        <th>Критичность</th>
                        <th>Подсказки по колонкам</th>
                        <th>Описание</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $rules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($rule->name); ?></td>
                            <td><?php echo e($rule->issue_type); ?></td>
                            <td><?php echo e($rule->severity); ?></td>
                            <td><?php echo e(implode(', ', $rule->column_hints ?? [])); ?></td>
                            <td><?php echo e($rule->description); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
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
<?php /**PATH C:\Users\minho\Desktop\Курсча_преддиплом\diploma-app\resources\views/rules/index.blade.php ENDPATH**/ ?>