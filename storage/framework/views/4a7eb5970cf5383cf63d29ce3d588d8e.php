<?php $__env->startSection('content'); ?>
<div class="max-w-xl mx-auto mt-10 bg-white p-8 rounded-xl shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-indigo-700">👤 My Profile</h2>

    <?php if(session('success')): ?>
        <div class="mb-4 p-3 bg-green-100 border-l-4 border-green-600 text-green-800 rounded">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="mb-4 p-3 bg-red-100 border-l-4 border-red-600 text-red-800 rounded">
            <ul class="list-disc pl-5 text-sm">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('profile.update')); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
            <input id="name" type="text" name="name" value="<?php echo e(old('name', $user->name)); ?>" class="w-full p-2 mt-1 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-400" required>
        </div>

        <div class="mb-4">
            <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
            <input id="username" type="text" name="username" value="<?php echo e(old('username', $user->username)); ?>" class="w-full p-2 mt-1 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-400" required>
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" type="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" class="w-full p-2 mt-1 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-400" required>
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">New Password <span class="text-gray-400 text-xs">(leave blank to keep current password)</span></label>
            <input id="password" type="password" name="password" class="w-full p-2 mt-1 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-400">
        </div>

        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="w-full p-2 mt-1 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-400">
        </div>

        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2 rounded-lg transition">
            💾 Save Profile
        </button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\formnew\formApp\resources\views/profile/index.blade.php ENDPATH**/ ?>