<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['current' => 'datasets']));

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

foreach (array_filter((['current' => 'datasets']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<aside class="sidebar">
    <nav class="sidebar-nav">
        <a href="/datasets" class="sidebar-link <?php echo e($current === 'datasets' ? 'is-active' : ''); ?>">Наборы</a>
        <a href="/datasets/create" class="sidebar-link <?php echo e($current === 'import' ? 'is-active' : ''); ?>">Импорт</a>
        <a href="/rules" class="sidebar-link <?php echo e($current === 'rules' ? 'is-active' : ''); ?>">Regex</a>
        <a href="/checks" class="sidebar-link <?php echo e($current === 'checks' ? 'is-active' : ''); ?>">Проверки</a>
        <a href="/issues" class="sidebar-link <?php echo e($current === 'issues' ? 'is-active' : ''); ?>">Инциденты</a>
        <a href="/duplicates" class="sidebar-link <?php echo e($current === 'duplicates' ? 'is-active' : ''); ?>">Дубликаты</a>
        <a href="/autofix" class="sidebar-link <?php echo e($current === 'autofix' ? 'is-active' : ''); ?>">AI-этап</a>
    </nav>

    <?php if(auth()->guard()->check()): ?>
        <div class="glass-note mt-5">
            <div class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Аккаунт</div>
            <div class="mt-3 text-base font-bold text-slate-900"><?php echo e(auth()->user()->name); ?></div>
            <div class="mt-1 text-sm text-slate-500"><?php echo e(auth()->user()->email); ?></div>
        </div>

        <form method="POST" action="/logout" class="mt-4">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button class="secondary-button w-full" type="submit">Выйти</button>
        </form>
    <?php else: ?>
        <div class="mt-5 grid gap-3">
            <a href="/login" class="secondary-button w-full">Вход</a>
            <a href="/register" class="primary-button w-full">Регистрация</a>
        </div>
    <?php endif; ?>
</aside>
<?php /**PATH C:\Users\minho\Desktop\Курсча_преддиплом\diploma-app\resources\views/components/forms/nav.blade.php ENDPATH**/ ?>