<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title' => '', 'current' => 'datasets']));

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

foreach (array_filter((['title' => '', 'current' => 'datasets']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<!doctype html>
<html lang="ru" data-theme="corporate">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo e($title ? "{$title} | DQ System" : 'DQ System'); ?></title>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    </head>
    <body>
        <div class="app-shell">
            <header class="app-header">
                <div>
                    <p class="badge badge-neutral badge-outline mb-3 rounded-full px-4 py-3">Rule-based Data Quality Platform</p>
                    <h1 class="app-title"><?php echo e($title ?: 'Система контроля качества данных'); ?></h1>
                    <p class="mt-4 max-w-3xl text-sm leading-7 text-slate-600 md:text-base">
                        Импорт таблиц, поиск проблем по regex, анализ дублей и подготовка спорных кейсов к AI-этапу.
                    </p>
                </div>
                <div class="app-brand">
                    <div class="text-xs uppercase tracking-[0.35em] text-slate-500">DQ System</div>
                    <div class="text-lg font-bold text-slate-800">Regex first, AI second</div>
                    <div class="text-sm text-slate-500">Adaptive workspace for data review</div>
                </div>
            </header>

            <div class="app-body">
                <?php if (isset($component)) { $__componentOriginal8905884cbf95490dcaafc6a90648b2ed = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8905884cbf95490dcaafc6a90648b2ed = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.nav','data' => ['current' => $current]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.nav'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['current' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($current)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8905884cbf95490dcaafc6a90648b2ed)): ?>
<?php $attributes = $__attributesOriginal8905884cbf95490dcaafc6a90648b2ed; ?>
<?php unset($__attributesOriginal8905884cbf95490dcaafc6a90648b2ed); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8905884cbf95490dcaafc6a90648b2ed)): ?>
<?php $component = $__componentOriginal8905884cbf95490dcaafc6a90648b2ed; ?>
<?php unset($__componentOriginal8905884cbf95490dcaafc6a90648b2ed); ?>
<?php endif; ?>

                <main class="content-area">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success mb-6 rounded-[22px] border border-emerald-200 bg-emerald-50/90 text-emerald-900 shadow-[0_14px_32px_rgba(16,185,129,0.10)]">
                            <span><?php echo e(session('success')); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-error mb-6 rounded-[22px] border border-rose-200 bg-rose-50/90 text-rose-900 shadow-[0_14px_32px_rgba(244,63,94,0.10)]">
                            <span><?php echo e(session('error')); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php echo e($slot); ?>

                </main>
            </div>
        </div>
    </body>
</html>
<?php /**PATH C:\Users\minho\Desktop\Курсча_преддиплом\diploma-app\resources\views/components/layout.blade.php ENDPATH**/ ?>