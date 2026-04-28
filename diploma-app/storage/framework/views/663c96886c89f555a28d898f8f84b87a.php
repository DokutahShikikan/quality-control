<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['current' => 'issues', 'datasetId' => null]));

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

foreach (array_filter((['current' => 'issues', 'datasetId' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="panel conflicts-panel">
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div class="space-y-2">
            <p class="text-sm font-bold uppercase tracking-[0.16em] text-slate-500">Конфликты</p>
            <h2 class="panel-title text-2xl md:text-[2rem]">
                <?php echo e($current === 'duplicates' ? 'Повторы в таблицах' : 'Ошибки в таблицах'); ?>

            </h2>
            <p class="text-sm leading-7 text-slate-600 md:text-base">
                Переключайтесь между ошибками и повторами внутри одного раздела.
            </p>
        </div>

        <div class="conflicts-tabs" role="tablist" aria-label="Раздел конфликтов">
            <a
                href="/issues<?php echo e($datasetId ? '?dataset='.$datasetId : ''); ?>"
                class="conflicts-tab <?php echo e($current === 'issues' ? 'is-active' : ''); ?>"
                aria-current="<?php echo e($current === 'issues' ? 'page' : 'false'); ?>"
            >
                Ошибки
            </a>
            <a
                href="/duplicates<?php echo e($datasetId ? '?dataset='.$datasetId : ''); ?>"
                class="conflicts-tab <?php echo e($current === 'duplicates' ? 'is-active' : ''); ?>"
                aria-current="<?php echo e($current === 'duplicates' ? 'page' : 'false'); ?>"
            >
                Повторы
            </a>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\minho\Desktop\Курсча_преддиплом\diploma-app\resources\views/components/conflicts-tabs.blade.php ENDPATH**/ ?>