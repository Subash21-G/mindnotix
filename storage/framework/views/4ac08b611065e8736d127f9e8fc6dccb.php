<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto py-10">
    <?php
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
        <img src="<?php echo e($logo); ?>" class="w-20 h-20 object-contain mx-auto mb-3" alt="Company Logo" />
    <?php endif; ?>
    <?php if($headerImage): ?>
        <img src="<?php echo e($headerImage); ?>" class="mb-4 w-32 h-32 mx-auto object-cover rounded-full border-4 border-indigo-200 shadow" alt="Form Banner"/>
    <?php endif; ?>
    <h2 class="text-3xl font-bold mb-1" style="color: <?php echo e($themeColor); ?>; text-align:<?php echo e($align); ?>">
        <?php echo e($title); ?>

    </h2>
    <?php if($subtitle): ?>
        <span class="text-base text-gray-500"><?php echo e($subtitle); ?></span>
    <?php endif; ?>
</header>

    <h2 class="text-2xl font-bold mb-6">Responses for: "<?php echo e($form->title); ?>"</h2>
    <?php if(session('success')): ?>
        <div class="bg-green-50 rounded p-3 mb-4 text-green-800"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <?php if($responses->isEmpty()): ?>
        <div class="text-gray-500 bg-gray-100 p-6 rounded-lg text-center">No responses yet.</div>
    <?php else: ?>
        <div class="overflow-x-auto shadow rounded-lg">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border-b">#</th>
                        <?php $__currentLoopData = $form->fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <th class="px-4 py-2 border-b"><?php echo e($field['label'] ?? $field['name'] ?? 'Field'); ?></th>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <th class="px-4 py-2 border-b">Submitted</th>
                        <th class="px-4 py-2 border-b"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $responses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $response): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="px-2 py-2 border-b"><?php echo e($responses->firstItem() + $i); ?></td>
                            <?php $__currentLoopData = $form->fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <td class="px-2 py-2 border-b">
                                    <?php
                                        $name = $field['name'] ?? '';
                                        $v = $response->answers[$name] ?? '';
                                        echo is_array($v) ? implode(', ', $v) : e($v);
                                    ?>
                                </td>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <td class="px-2 py-2 border-b text-xs"><?php echo e($response->created_at->format('Y-m-d H:i')); ?></td>
                            <td class="px-2 py-2 border-b">

<form action="<?php echo e(route('responses.destroy', [$form, $response->id])); ?>" method="POST" onsubmit="return confirm('Delete this response?');" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button class="text-xs text-red-600 hover:underline" type="submit" title="Delete Response">🗑️</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <div class="mt-6"><?php echo e($responses->links()); ?></div>
    <?php endif; ?>
    <a href="<?php echo e(route('dashboard')); ?>" class="mt-8 inline-block text-indigo-700 hover:underline">&larr; Back to Dashboard</a>



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


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\formnew\formApp\resources\views/responses/index.blade.php ENDPATH**/ ?>