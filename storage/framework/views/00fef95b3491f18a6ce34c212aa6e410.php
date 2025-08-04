<?php $__env->startSection('content'); ?>
<div class="flex justify-center py-10 px-4 bg-gray-100 min-h-screen">
    <div class="w-full max-w-2xl">

        
        <?php
            use Illuminate\Support\Str;
            $header = is_array($form->header ?? null) ? $form->header : [];
            $themeColor = $header['theme_color'] ?? '#6366f1';
            $align = $header['title_align'] ?? 'center';
            $headerImage = $header['image'] ?? null;
            $logo = $header['logo'] ?? null;
            $title = !empty($header['title']) && is_string($header['title']) ? $header['title'] : ($form->title ?? 'Untitled Form');
            $subtitle = !empty($header['subtitle']) && is_string($header['subtitle']) ? $header['subtitle'] : null;
        ?>
        <header class="mb-8 flex flex-col items-center text-center">
            <?php if($logo): ?>
                <img src="<?php echo e(Str::startsWith($logo, ['http://', 'https://', 'data:']) ? $logo : asset('storage/' . ltrim($logo, '/'))); ?>"
                    class="w-20 h-20 object-contain mx-auto mb-3" alt="Company Logo" />
            <?php endif; ?>
            <?php if($headerImage): ?>
                <img src="<?php echo e(Str::startsWith($headerImage, ['http://', 'https://', 'data:']) ? $headerImage : asset('storage/' . ltrim($headerImage, '/'))); ?>"
                    class="mb-4 w-32 h-32 mx-auto object-cover rounded-full border-4 border-indigo-200 shadow"
                    alt="Form Banner" />
            <?php endif; ?>
            <h2 class="text-3xl font-bold mb-1"
                style="color: <?php echo e($themeColor); ?>; text-align:<?php echo e($align); ?>">
                <?php echo e($title); ?>

            </h2>
            <?php if($subtitle): ?>
                <span class="text-base text-gray-500"><?php echo e($subtitle); ?></span>
            <?php endif; ?>
        </header>

        
        <?php if(session('success')): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg" role="alert">
                <p class="font-bold">Success!</p>
                <p><?php echo e(session('success')); ?></p>
            </div>
        <?php endif; ?>
        <?php if($errors->any()): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg" role="alert">
                <p class="font-bold">Please fix the following errors:</p>
                <ul class="mt-2 list-disc list-inside">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        
        <div class="bg-white text-gray-900 rounded-2xl shadow-xl p-8">
            <form action="<?php echo e(route('responses.store', $form)); ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                <?php echo csrf_field(); ?>
                <?php if(isset($form->fields) && is_array($form->fields) && count($form->fields)): ?>
                    <?php $__currentLoopData = $form->fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $name     = isset($field['name']) && is_string($field['name']) ? $field['name'] : '';
                            $label    = isset($field['label']) && is_string($field['label']) ? $field['label'] : ($name ?: 'Field');
                            $type     = $field['type'] ?? 'short_answer';
                            $required = isset($field['rules']) && is_string($field['rules']) && str_contains($field['rules'], 'required');
                            $options  = (isset($field['options']) && is_array($field['options'])) ? $field['options'] : [];
                        ?>
                        <div>
                            <label for="<?php echo e($name); ?>" class="block font-semibold mb-2 text-gray-800">
                                <?php echo e($label); ?><?php if($required): ?><span class="text-red-500">*</span><?php endif; ?>
                            </label>
                            <?php switch($type):
                                case ('short_answer'): ?>
                                    <input type="text" name="<?php echo e($name); ?>" id="<?php echo e($name); ?>"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2"
                                        value="<?php echo e(old($name)); ?>"
                                        <?php if($required): ?> required <?php endif; ?>>
                                    <?php break; ?>
                                <?php case ('paragraph'): ?>
                                    <textarea name="<?php echo e($name); ?>" id="<?php echo e($name); ?>"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2"
                                        rows="4"
                                        <?php if($required): ?> required <?php endif; ?>><?php echo e(old($name)); ?></textarea>
                                    <?php break; ?>
                                <?php case ('email'): ?>
                                    <input type="email" name="<?php echo e($name); ?>" id="<?php echo e($name); ?>"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2"
                                        value="<?php echo e(old($name)); ?>"
                                        <?php if($required): ?> required <?php endif; ?>>
                                    <?php break; ?>
                                <?php case ('mobile'): ?>
                                    <input type="tel" name="<?php echo e($name); ?>" id="<?php echo e($name); ?>"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2"
                                        value="<?php echo e(old($name)); ?>"
                                        <?php if($required): ?> required <?php endif; ?>>
                                    <?php break; ?>
                                <?php case ('single_choice'): ?>
                                    <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <label class="flex items-center space-x-3 mb-2 p-3 border rounded-lg hover:bg-gray-50">
                                            <input type="radio" name="<?php echo e($name); ?>" value="<?php echo e($option); ?>"
                                                <?php if(old($name) == $option): ?> checked <?php endif; ?>
                                                <?php if($required): ?> required <?php endif; ?>>
                                            <span><?php echo e($option); ?></span>
                                        </label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php break; ?>
                                <?php case ('multiple_choice'): ?>
                                    <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <label class="flex items-center space-x-3 mb-2 p-3 border rounded-lg hover:bg-gray-50">
                                            <input type="checkbox" name="<?php echo e($name); ?>[]" value="<?php echo e($option); ?>"
                                                <?php if(is_array(old($name)) && in_array($option, old($name))): ?> checked <?php endif; ?>>
                                            <span><?php echo e($option); ?></span>
                                        </label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php break; ?>
                                <?php case ('file'): ?>
                                    <input type="file" name="<?php echo e($name); ?>" id="<?php echo e($name); ?>" class="w-full"
                                        <?php if($required): ?> required <?php endif; ?>>
                                    <?php break; ?>
                                <?php case ('location'): ?>
                                    <input type="text" name="<?php echo e($name); ?>" id="<?php echo e($name); ?>"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2"
                                        value="<?php echo e(old($name)); ?>"
                                        <?php if($required): ?> required <?php endif; ?>>
                                    <?php break; ?>
                                <?php case ('age'): ?>
                                    <input type="number" name="<?php echo e($name); ?>" id="<?php echo e($name); ?>"
                                        min="0"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2"
                                        value="<?php echo e(old($name)); ?>"
                                        <?php if($required): ?> required <?php endif; ?>>
                                    <?php break; ?>
                                <?php default: ?>
                                    <input type="text" name="<?php echo e($name); ?>" id="<?php echo e($name); ?>"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2"
                                        value="<?php echo e(old($name)); ?>"
                                        <?php if($required): ?> required <?php endif; ?>>
                            <?php endswitch; ?>
                            <?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="text-gray-400">No fields defined for this form.</div>
                <?php endif; ?>

                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-xl transition shadow-lg">
                    🚀 Submit Response
                </button>
            </form>
        </div>

        
        <?php
            $footer = is_array($form->footer ?? null) ? $form->footer : [];
            $showFooter = !isset($footer['show_footer']) || $footer['show_footer'];
        ?>
        <?php if($showFooter): ?>
        <footer class="mt-10 mb-3 text-center text-gray-600 space-y-3">
            <?php if(!empty($footer['website'])): ?>
                <div>
                    <a href="<?php echo e($footer['website']); ?>" target="_blank" rel="noopener" class="underline hover:text-indigo-500 transition">
                        🌐 <?php echo e(parse_url($footer['website'], PHP_URL_HOST) ?? $footer['website']); ?>

                    </a>
                </div>
            <?php endif; ?>
            <?php if(!empty($footer['support_numbers']) && is_array($footer['support_numbers'])): ?>
                <div>
                    <?php $__currentLoopData = $footer['support_numbers']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $number): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($number): ?>
                        <span class="inline-block mr-2">☎️ <a href="tel:<?php echo e($number); ?>" class="underline hover:text-indigo-500"><?php echo e($number); ?></a></span>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
            <?php if(!empty($footer['support_emails']) && is_array($footer['support_emails'])): ?>
                <div>
                    <?php $__currentLoopData = $footer['support_emails']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $email): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($email): ?>
                        <span class="inline-block mr-2">📧 <a href="mailto:<?php echo e($email); ?>" class="underline hover:text-indigo-500"><?php echo e($email); ?></a></span>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
            <?php if(!empty($footer['social']) && is_array($footer['social'])): ?>
                <div>
                    <?php $__currentLoopData = $footer['social']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($link): ?>
                        <a href="<?php echo e($link); ?>" target="_blank" class="inline-block mr-2 underline hover:text-indigo-500">
                            🔗 <?php echo e(parse_url($link, PHP_URL_HOST) ?? $link); ?>

                        </a>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
            <?php if(!empty($footer['extra'])): ?>
                <div class="text-xs text-gray-500 mt-2"><?php echo e($footer['extra']); ?></div>
            <?php endif; ?>
            <div class="text-xs text-gray-400 mt-2">
                &copy; <?php echo e(date('Y')); ?> Your Company. All rights reserved.
            </div>
        </footer>
        <?php endif; ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\formnew\formApp\resources\views/forms/show.blade.php ENDPATH**/ ?>