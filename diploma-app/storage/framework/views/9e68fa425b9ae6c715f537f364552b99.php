<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title', 'description' => null]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['title', 'description' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div <?php echo e($attributes->class('flex items-start justify-between gap-4')); ?>>
    <div>
        <h2 class="panel-title"><?php echo e($title); ?></h2>
        <?php if($description): ?>
            <p class="mt-3 text-base leading-7 text-slate-600 md:text-lg"><?php echo e($description); ?></p>
        <?php endif; ?>
    </div>

    <?php if(trim($slot) !== ''): ?>
        <div class="shrink-0">
            <?php echo e($slot); ?>

        </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\Users\minho\Desktop\Курсча_преддиплом\diploma-app\resources\views/components/section-header.blade.php ENDPATH**/ ?>