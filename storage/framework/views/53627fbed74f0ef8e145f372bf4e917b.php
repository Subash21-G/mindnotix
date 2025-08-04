<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto mt-10 p-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">🧾 Your Forms</h1>
        <a href="<?php echo e(route('forms.create')); ?>"
           class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
            ➕ Create New Form
        </a>
    </div>

    <?php if($forms->isEmpty()): ?>
        <div class="text-center text-gray-600 mt-20 bg-gray-50 p-10 rounded-lg">
            <h3 class="text-xl font-semibold">No forms yet!</h3>
            <p class="mt-2">Ready to collect some data? Create your first form now.</p>
            <a href="<?php echo e(route('forms.create')); ?>" class="mt-4 inline-block bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                Create First Form
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__currentLoopData = $forms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $form): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 mb-2 truncate"><?php echo e($form->title); ?></h2>
                        <p class="text-sm text-gray-700 mb-4 break-words h-16"><?php echo e(Str::limit($form->description, 100)); ?></p>
                    </div>
                    <div class="mt-4 border-t pt-4 flex justify-end gap-4">
                        <a href="<?php echo e(route('forms.show', $form)); ?>" class="text-blue-600 hover:underline text-sm font-medium">🔗 Preview & Share</a>
                        <a href="<?php echo e(route('responses.index', $form)); ?>" class="text-green-600 hover:underline text-sm font-medium">📊 View Responses</a>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\formnew\formApp\resources\views/forms/index.blade.php ENDPATH**/ ?>