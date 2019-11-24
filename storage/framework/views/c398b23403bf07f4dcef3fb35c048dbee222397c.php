<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<?php echo $__env->make('layouts/desktop/head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<body class="page-header-fixed sidemenu-closed-hidelogo page-content-white page-md header-white white-sidebar-color logo-indigo sidemenu-closed">
<div id="app" class="page-wrapper">
    <?php echo $__env->make('layouts/desktop/top_nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="page-container">
        <?php echo $__env->make('layouts/desktop/'.Auth::user()->getCurrentRoleSlug().'/sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="page-content-wrapper">
            <div class="page-content">
                <?php if($autoThumbnail): ?>
                <?php echo e(\App\Utils\UI\Thumbnail::Print($pageTitle)); ?>

                <?php endif; ?>
                <?php echo $__env->make('reusable_elements.section.session_flash_msg', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
        <?php echo $__env->make('layouts/desktop/sidebar_chat', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    <?php echo $__env->make('layouts/desktop/footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>
<?php echo $__env->make('layouts/desktop/js', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>
</html>
<?php /**PATH D:\wamp64\www\etx-t\purfect-teacher-backend\resources\views/layouts/app.blade.php ENDPATH**/ ?>