<?php if (isset($component)) { $__componentOriginal23a33f287873b564aaf305a1526eada4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal23a33f287873b564aaf305a1526eada4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout','data' => ['title' => 'Вход в систему','current' => 'auth']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Вход в систему','current' => 'auth']); ?>
    <div class="mx-auto max-w-2xl">
        <div class="panel">
            <h2 class="panel-title">Вход в систему</h2>
            <p class="mt-3 text-lg text-slate-600">
                Открой рабочее пространство сервиса проверки, поиска дубликатов и автоисправления данных.
            </p>

            <form action="/login" method="POST" novalidate class="mt-8 space-y-6">
                <?php echo csrf_field(); ?>

                <div class="form-field">
                    <label class="form-label" for="email">Email</label>
                    <input class="text-field" id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" placeholder="operator@dq.local" required>
                    <?php if (isset($component)) { $__componentOriginal8515795c137f433faaa3099468a4ec61 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8515795c137f433faaa3099468a4ec61 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.error','data' => ['name' => 'email']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'email']); ?>
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
                </div>

                <div class="form-field">
                    <label class="form-label" for="password">Пароль</label>
                    <input id="password" type="password" class="text-field" name="password" placeholder="Введите пароль" required>
                    <?php if (isset($component)) { $__componentOriginal8515795c137f433faaa3099468a4ec61 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8515795c137f433faaa3099468a4ec61 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.error','data' => ['name' => 'password']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'password']); ?>
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
                </div>

                <div class="flex flex-wrap gap-4">
                    <button class="primary-button" type="submit">Войти</button>
                    <a href="/register" class="secondary-button">Создать аккаунт</a>
                </div>
            </form>
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
<?php /**PATH C:\Users\minho\Desktop\Курсча_преддиплом\diploma-app\resources\views/auth/login.blade.php ENDPATH**/ ?>