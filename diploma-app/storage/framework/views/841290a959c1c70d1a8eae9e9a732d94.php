<?php if (isset($component)) { $__componentOriginal23a33f287873b564aaf305a1526eada4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal23a33f287873b564aaf305a1526eada4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout','data' => ['title' => 'Наборы данных','current' => 'datasets']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Наборы данных','current' => 'datasets']); ?>
    <section class="space-y-8">
        <div class="hero-panel">
            <div class="grid gap-8 xl:grid-cols-[1.2fr_0.8fr] xl:items-end">
                <div>
                    <div class="badge badge-info badge-outline rounded-full border-white/30 bg-white/10 px-4 py-3 text-white">Data Quality Workflow</div>
                    <h2 class="mt-5 text-3xl font-black tracking-tight text-white md:text-5xl">
                        От импорта Excel к управляемому исправлению ошибок и дублей
                    </h2>
                    <p class="mt-5 max-w-3xl text-base leading-8 text-blue-50 md:text-lg">
                        Пользователь загружает Excel или CSV, сервис находит пустые значения, нарушения формата по regex,
                        дубликаты строк и дает управляемые действия: исправить, игнорировать или передать спорные случаи на AI-этап.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="/datasets/create" class="primary-button">Импортировать таблицу</a>
                        <a href="/issues" class="secondary-button border-white/20 bg-white/10 text-white hover:bg-white/15">Открыть инциденты</a>
                    </div>
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    <div class="rounded-[24px] border border-white/15 bg-white/10 p-5 backdrop-blur">
                        <div class="text-xs font-bold uppercase tracking-[0.2em] text-blue-100">Regex layer</div>
                        <div class="mt-3 text-3xl font-black text-white"><?php echo e($metrics['open_issues']); ?></div>
                        <p class="mt-2 text-sm leading-6 text-blue-100">Проблемы формата и пустые значения, которые можно разбирать детерминированно.</p>
                    </div>
                    <div class="rounded-[24px] border border-white/15 bg-white/10 p-5 backdrop-blur">
                        <div class="text-xs font-bold uppercase tracking-[0.2em] text-blue-100">Duplicate review</div>
                        <div class="mt-3 text-3xl font-black text-white"><?php echo e($metrics['open_duplicates']); ?></div>
                        <p class="mt-2 text-sm leading-6 text-blue-100">Найденные кандидаты в дубли для ручного решения или удаления.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="metric-grid">
            <div class="metric-card">
                <div class="metric-label">Всего наборов</div>
                <div class="metric-value"><?php echo e($metrics['datasets']); ?></div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Открытые инциденты</div>
                <div class="metric-value"><?php echo e($metrics['open_issues']); ?></div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Кандидаты в дубликаты</div>
                <div class="metric-value"><?php echo e($metrics['open_duplicates']); ?></div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Готово к AI-этапу</div>
                <div class="metric-value"><?php echo e($metrics['ready_for_ai']); ?></div>
            </div>
        </div>

        <?php if($datasets->isNotEmpty()): ?>
            <div class="grid grid-cols-1 gap-6 2xl:grid-cols-2">
                <?php $__currentLoopData = $datasets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dataset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if (isset($component)) { $__componentOriginala7bbc51fc472b0a7484f2f4dfdd991c1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala7bbc51fc472b0a7484f2f4dfdd991c1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dataset-card','data' => ['dataset' => $dataset]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dataset-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['dataset' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($dataset)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala7bbc51fc472b0a7484f2f4dfdd991c1)): ?>
<?php $attributes = $__attributesOriginala7bbc51fc472b0a7484f2f4dfdd991c1; ?>
<?php unset($__attributesOriginala7bbc51fc472b0a7484f2f4dfdd991c1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala7bbc51fc472b0a7484f2f4dfdd991c1)): ?>
<?php $component = $__componentOriginala7bbc51fc472b0a7484f2f4dfdd991c1; ?>
<?php unset($__componentOriginala7bbc51fc472b0a7484f2f4dfdd991c1); ?>
<?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="panel max-w-4xl">
                <h2 class="panel-title">Наборы пока не загружены</h2>
                <p class="mt-4 text-base leading-8 text-slate-600 md:text-lg">
                    Начни с импорта файла. Поддерживаются CSV и базовый XLSX. После загрузки набор автоматически проходит
                    первичную проверку и появляется в панели для последующего разбора.
                </p>
                <div class="mt-8">
                    <a href="/datasets/create" class="primary-button">Загрузить первый файл</a>
                </div>
            </div>
        <?php endif; ?>
    </section>
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
<?php /**PATH C:\Users\minho\Desktop\Курсча_преддиплом\diploma-app\resources\views/datasets/index.blade.php ENDPATH**/ ?>