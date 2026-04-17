<?php if (isset($component)) { $__componentOriginal23a33f287873b564aaf305a1526eada4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal23a33f287873b564aaf305a1526eada4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout','data' => ['title' => 'Импорт набора','current' => 'import']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Импорт набора','current' => 'import']); ?>
    <div class="grid gap-8 xl:grid-cols-[1.2fr_0.8fr]">
        <section class="panel">
            <h2 class="panel-title">Загрузка исходной таблицы</h2>
            <p class="mt-4 text-lg leading-8 text-slate-700">
                Загрузи Excel или CSV с проблемными данными. Сразу после сохранения система создаст строки набора,
                выполнит regex-проверки и сформирует список ошибок и дубликатов.
            </p>

            <form method="POST" action="/datasets" enctype="multipart/form-data" class="mt-8 space-y-6">
                <?php echo csrf_field(); ?>

                <label class="form-field">
                    <span class="form-label">Название набора</span>
                    <input class="text-field" type="text" name="name" value="<?php echo e(old('name')); ?>" placeholder="Например, Клиенты апрель 2026">
                    <?php if (isset($component)) { $__componentOriginal8515795c137f433faaa3099468a4ec61 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8515795c137f433faaa3099468a4ec61 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.error','data' => ['name' => 'name']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'name']); ?>
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

                <label class="form-field">
                    <span class="form-label">Описание задачи</span>
                    <textarea class="text-area" name="description" rows="5" placeholder="Что ожидается проверить: email, телефоны, даты, дубликаты..."><?php echo e(old('description')); ?></textarea>
                    <?php if (isset($component)) { $__componentOriginal8515795c137f433faaa3099468a4ec61 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8515795c137f433faaa3099468a4ec61 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.error','data' => ['name' => 'description']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'description']); ?>
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

                <label class="form-field">
                    <span class="form-label">Файл данных</span>
                    <input class="file-input w-full border border-[#c7d4e6] bg-white text-lg" type="file" name="source_file" accept=".csv,.txt,.xlsx">
                    <?php if (isset($component)) { $__componentOriginal8515795c137f433faaa3099468a4ec61 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8515795c137f433faaa3099468a4ec61 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.error','data' => ['name' => 'source_file']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'source_file']); ?>
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

                <label class="flex items-center gap-3 rounded-none border border-[#c7d4e6] bg-[#f7fbff] px-4 py-4 text-lg text-slate-700">
                    <input class="checkbox checkbox-primary rounded-none" type="checkbox" name="deepseek_enabled" value="1" <?php echo e(old('deepseek_enabled') ? 'checked' : ''); ?>>
                    <span>Подготовить набор к AI-этапу с DeepSeek после regex-исправлений</span>
                </label>

                <div class="flex flex-wrap gap-4">
                    <button type="submit" class="primary-button">Сохранить и проверить</button>
                    <a href="/datasets" class="secondary-button">Назад к наборам</a>
                </div>
            </form>
        </section>

        <aside class="panel">
            <h3 class="soft-title">Что произойдет после загрузки</h3>
            <div class="mt-6 space-y-4 text-lg leading-8 text-slate-700">
                <p>1. Таблица разбирается на заголовки и строки.</p>
                <p>2. Для строк строятся нормализованные отпечатки для поиска полных дублей.</p>
                <p>3. Включаются regex-правила для email, телефонов и дат.</p>
                <p>4. Пустые значения и нарушения формата попадают в инциденты.</p>
                <p>5. Дальше ты выбираешь: исправить безопасно, проигнорировать или запускать AI-этап.</p>
            </div>
        </aside>
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
<?php /**PATH C:\Users\minho\Desktop\Курсча_преддиплом\diploma-app\resources\views/datasets/create.blade.php ENDPATH**/ ?>