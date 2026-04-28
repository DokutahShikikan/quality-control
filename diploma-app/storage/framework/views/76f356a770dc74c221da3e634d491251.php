<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name',
    'label',
    'accept' => null,
]));

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

foreach (array_filter(([
    'name',
    'label',
    'accept' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<label class="form-field">
    <span class="form-label"><?php echo e($label); ?></span>
    <input
        <?php echo e($attributes->class('file-input w-full rounded-[20px] border border-slate-200 bg-white text-base shadow-[0_10px_24px_rgba(148,163,184,0.08)]')); ?>

        type="file"
        name="<?php echo e($name); ?>"
        <?php if($accept): ?> accept="<?php echo e($accept); ?>" <?php endif; ?>
    >
    <?php if (isset($component)) { $__componentOriginal8515795c137f433faaa3099468a4ec61 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8515795c137f433faaa3099468a4ec61 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.error','data' => ['name' => $name]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($name)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8515795c137f433faaa3099468a4ec61)): ?>
<?php $attributes = $__attributesOriginal8515795c137f433faaa3099468a4ec61; ?>
<?php unset($__attributesOriginal8515795c137f433faaa3099468a4ec61); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8515795c137f433faaa3099468a4ec61)): ?>
<?php $component = $__componentOriginal8515795c137f433faaa3099468a4ec61; ?>
<?php unset($__componentOriginal8515795c137f433faaa3099468a4ec61); ?>
<?php endif; ?>
</label>
<?php /**PATH C:\Users\minho\Desktop\Курсча_преддиплом\diploma-app\resources\views/components/forms/file-field.blade.php ENDPATH**/ ?>