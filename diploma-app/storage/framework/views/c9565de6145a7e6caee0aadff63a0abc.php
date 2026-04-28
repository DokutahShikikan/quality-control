<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name',
    'label',
    'checked' => false,
    'value' => 1,
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
    'checked' => false,
    'value' => 1,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<label <?php echo e($attributes->class('flex items-center gap-3 rounded-[22px] border border-slate-200 bg-slate-50/90 px-4 py-4 text-base text-slate-700')); ?>>
    <input
        class="checkbox checkbox-primary"
        type="checkbox"
        name="<?php echo e($name); ?>"
        value="<?php echo e($value); ?>"
        <?php if(old($name, $checked)): echo 'checked'; endif; ?>
    >
    <span><?php echo e($label); ?></span>
</label>
<?php /**PATH C:\Users\minho\Desktop\Курсча_преддиплом\diploma-app\resources\views/components/forms/checkbox-card.blade.php ENDPATH**/ ?>