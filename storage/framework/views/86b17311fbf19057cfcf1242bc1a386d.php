<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

?>

<div>
    <h1>Welcome back</h1>
    <p class="sub">Sign in to pick up where you left off.</p>

    <!-- TABS -->
    <div class="tabs" role="tablist">
      <button type="button" class="tab" wire:click="usePassword" role="tab" aria-selected="<?php echo e($form->loginMode === 'password' ? 'true' : 'false'); ?>">Password</button>
      <button type="button" class="tab" wire:click="useOtp" role="tab" aria-selected="<?php echo e($form->loginMode === 'otp' ? 'true' : 'false'); ?>">One-time code</button>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($form->loginMode === 'password'): ?>
        <!-- PASSWORD PANEL -->
        <div class="panel active" role="tabpanel">
            <form wire:submit="login" autocomplete="on">
                <div class="field">
                    <label for="pw-email">Email or Mobile</label>
                    <input type="text" wire:model="form.identifier" id="pw-email" placeholder="you@company.com" autocomplete="email" required>
                    <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('form.identifier'),'class' => 'mt-1 text-red-500 text-xs']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('form.identifier')),'class' => 'mt-1 text-red-500 text-xs']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                </div>
                <div class="field" x-data="{ show: false }">
                    <label for="pw-password">Password</label>
                    <div class="input-wrap">
                        <input x-bind:type="show ? 'text' : 'password'" wire:model="form.password" id="pw-password" placeholder="Enter your password" autocomplete="current-password" required>
                        <button type="button" class="pw-toggle" @click="show = !show" x-text="show ? 'Hide' : 'Show'">Show</button>
                    </div>
                    <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('form.password'),'class' => 'mt-1 text-red-500 text-xs']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('form.password')),'class' => 'mt-1 text-red-500 text-xs']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                </div>
                <div class="row-between">
                    <label class="remember">
                        <input type="checkbox" wire:model="form.remember" id="remember">
                        Remember me
                    </label>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('password.request')): ?>
                        <a href="<?php echo e(route('password.request')); ?>" wire:navigate class="link">Forgot password?</a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <button type="submit" class="primary">Sign in</button>
            </form>
        </div>
    <?php else: ?>
        <!-- OTP PANEL -->
        <div class="panel active" role="tabpanel">
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$form->otpSent): ?>
                <!-- Step 1: request code -->
                <div>
                    <form wire:submit="sendOtp">
                        <div class="field">
                            <label for="otp-email">Email or Mobile</label>
                            <input type="text" wire:model="form.identifier" id="otp-email" placeholder="you@company.com" required>
                            <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('form.identifier'),'class' => 'mt-1 text-red-500 text-xs']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('form.identifier')),'class' => 'mt-1 text-red-500 text-xs']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                        </div>
                        <p class="hint">We'll send a 6-digit code to this address. It's valid for 10 minutes.</p>
                        <button type="submit" class="primary" style="margin-top:16px;">Send code</button>
                    </form>
                </div>
            <?php else: ?>
                <!-- Step 2: enter code -->
                <div 
                    x-data="{
                        digits: [' ', ' ', ' ', ' ', ' ', ' '],
                        sync() {
                            $wire.set('form.otp', this.digits.join('').replace(/\s/g, ''));
                        },
                        focusBox(i) {
                            const el = this.$refs['otp' + i];
                            if (el) { el.focus(); el.select(); }
                        },
                        onInput(i, e) {
                            const raw = (e.target.value || '').replace(/\D/g, '');
                            const v   = raw.slice(-1);
                            this.digits[i] = v || ' ';
                            e.target.value = v;
                            this.sync();
                            if (v && i < 5) this.focusBox(i + 1);
                        },
                        onKey(i, e) {
                            if (e.key === 'Backspace') {
                                if ((this.digits[i] || '').trim() !== '') {
                                    this.digits[i] = ' ';
                                    e.target.value = '';
                                    this.sync();
                                } else if (i > 0) {
                                    this.digits[i - 1] = ' ';
                                    this.$refs['otp' + (i - 1)].value = '';
                                    this.sync();
                                    this.focusBox(i - 1);
                                }
                                e.preventDefault();
                            }
                        },
                        onPaste(e) {
                            e.preventDefault();
                            const text = (e.clipboardData || window.clipboardData).getData('text') || '';
                            const cleaned = text.replace(/\D/g, '').slice(0, 6).split('');
                            for (let i = 0; i < 6; i++) {
                                const ch = cleaned[i] || ' ';
                                this.digits[i] = ch;
                                if (this.$refs['otp' + i]) this.$refs['otp' + i].value = (ch.trim() || '');
                            }
                            this.sync();
                            const next = Math.min(cleaned.length, 5);
                            this.focusBox(next);
                        }
                    }">
                    
                    <p class="otp-sent-to">Code sent to <strong><?php echo e($form->maskedOtpTarget); ?></strong></p>

                    <form wire:submit="login">
                        <div class="field">
                            <label>Enter the 6-digit code</label>
                            <div class="otp-boxes" @paste="onPaste($event)">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 0; $i < 6; $i++): ?>
                                    <input type="text" inputmode="numeric" maxlength="1" x-ref="otp<?php echo e($i); ?>"
                                           @input="onInput(<?php echo e($i); ?>, $event)" @keydown="onKey(<?php echo e($i); ?>, $event)" autocomplete="off">
                                <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('form.otp'),'class' => 'mt-1 text-red-500 text-xs']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('form.otp')),'class' => 'mt-1 text-red-500 text-xs']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                        </div>

                        <div class="resend-row" x-data="{ seconds: 30, timer: null }" x-init="
                            timer = setInterval(() => { if (seconds > 0) seconds--; else clearInterval(timer); }, 1000);
                        ">
                            <span x-show="seconds > 0">Resend code in <span x-text="seconds"></span>s</span>
                            <button type="button" @click="$wire.resendOtp(); seconds = 30; timer = setInterval(() => { if (seconds > 0) seconds--; else clearInterval(timer); }, 1000);" x-bind:disabled="seconds > 0">Resend code</button>
                        </div>

                        <button type="submit" class="primary">Verify &amp; sign in</button>
                        <button type="button" wire:click="cancelOtp" class="change-email">Use a different email</button>
                    </form>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div><?php /**PATH /home/ubuntu/Desktop/flipflop/payout-system/resources/views/livewire/pages/auth/login.blade.php ENDPATH**/ ?>