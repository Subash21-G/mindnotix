<?php $__env->startSection('content'); ?>
<div class="flex mt-10 max-w-6xl mx-auto">
    <!-- Sidebar -->
    <aside class="w-64 bg-white rounded-xl shadow-lg p-6 mr-8 hidden md:block">
        <div class="flex flex-col items-center pb-6 border-b mb-6">
            <div class="bg-indigo-50 rounded-full w-20 h-20 flex items-center justify-center text-3xl text-indigo-600 font-bold mb-2">
                <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

            </div>
            <h2 class="text-lg font-semibold text-gray-800"><?php echo e($user->name); ?></h2>
            <p class="text-gray-500 text-sm"><?php echo e($user->username); ?></p>
            <p class="text-gray-500 text-xs"><?php echo e($user->email); ?></p>
        </div>
        <div class="mb-6">
            <h3 class="text-indigo-600 font-bold text-2xl text-center"><?php echo e($formCount); ?></h3>
            <p class="text-gray-700 text-center">Forms Created</p>
        </div>
        <a href="<?php echo e(route('profile.index')); ?>" class="block bg-indigo-100 text-indigo-700 font-medium rounded px-3 py-2 mt-4 text-center hover:bg-indigo-200">
            View Profile
        </a>
        <form action="<?php echo e(route('logout')); ?>" method="POST" class="mt-2">
            <?php echo csrf_field(); ?>
            <button type="submit" class="w-full text-red-600 hover:underline">Logout</button>
        </form>
    </aside>

    <!-- Main Content -->
    <main class="flex-1">
        <div class="bg-white rounded-xl shadow-xl p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">My Forms</h2>
                <a href="<?php echo e(route('forms.create')); ?>" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition">
                    ➕ Create New Form
                </a>
            </div>

            
            <?php if(session('success')): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <?php if($forms->isEmpty()): ?>
                <div class="text-center text-gray-600 bg-gray-50 p-10 rounded-lg">
                    <h3 class="text-xl font-semibold">No forms yet!</h3>
                    <p class="mt-2">Create your first form now.</p>
                    <a href="<?php echo e(route('forms.create')); ?>" class="mt-4 inline-block bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                        Create Form
                    </a>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php $__currentLoopData = $forms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $form): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white rounded-xl shadow p-6 flex flex-col">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 truncate"><?php echo e($form->title ?? 'Untitled'); ?></h3>
                            <p class="text-gray-700 mb-4 break-words"><?php echo e(Str::limit($form->description, 100)); ?></p>
                            <div class="mt-auto flex flex-wrap justify-end gap-4 border-t pt-4">
                                <a href="<?php echo e(route('forms.show', $form)); ?>" target="_blank" rel="noopener" class="text-blue-600 hover:underline text-sm font-medium" title="Preview and Share Form">
                                    🔗 Preview & Share
                                </a>
                                <a href="<?php echo e(route('responses.index', $form)); ?>" class="text-green-600 hover:underline text-sm font-medium" title="View Form Responses">
                                    📊 Responses
                                </a>
                                <a href="<?php echo e(route('forms.edit', $form)); ?>" class="text-indigo-500 hover:underline text-sm font-medium" title="Edit Form">
                                    ✏️ Edit
                                </a>
                                <form action="<?php echo e(route('forms.destroy', $form)); ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this form and all its responses?');" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-red-600 hover:underline text-sm font-medium" title="Delete Form">
                                        🗑️ Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\formnew\formApp\resources\views/dashboard/index.blade.php ENDPATH**/ ?>