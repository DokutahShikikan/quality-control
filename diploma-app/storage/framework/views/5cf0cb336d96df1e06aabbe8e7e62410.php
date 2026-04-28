<?php
    $triggerLabel = match ($latestRun?->trigger_source) {
        'import' => 'После загрузки',
        'manual' => 'Вручную',
        'regex_fix' => 'После исправления по шаблону',
        'duplicate_resolution' => 'После разбора повторов',
        'deepseek_fix' => 'После исправления через ИИ',
        default => 'Не указано',
    };

    $runStatusLabel = match ($latestRun?->status) {
        'completed' => 'Завершено',
        'running' => 'В процессе',
        'failed' => 'С ошибкой',
        default => 'Не указано',
    };
?>

<aside class="panel" id="dataset-status-card">
    <h3 class="soft-title">Последняя проверка</h3>
    <?php if($latestRun): ?>
        <div class="mt-6 space-y-4 text-lg text-slate-700">
            <p><strong>Как запустили:</strong> <?php echo e($triggerLabel); ?></p>
            <p><strong>Состояние:</strong> <?php echo e($runStatusLabel); ?></p>
            <p><strong>Строк проверено:</strong> <?php echo e($latestRun->total_rows); ?></p>
            <p><strong>Ошибок найдено:</strong> <?php echo e($latestRun->issues_count); ?></p>
            <p><strong>Повторов найдено:</strong> <?php echo e($latestRun->duplicate_pairs_count); ?></p>
        </div>
    <?php else: ?>
        <p class="mt-6 text-lg text-slate-600">Проверка еще не запускалась.</p>
    <?php endif; ?>
</aside>
<?php /**PATH C:\Users\minho\Desktop\Курсча_преддиплом\diploma-app\resources\views/datasets/partials/dataset-status-card.blade.php ENDPATH**/ ?>