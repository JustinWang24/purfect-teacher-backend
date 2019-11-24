<div class="page-bar">
    <div class="page-title-breadcrumb">
        <div class=" pull-left">
            <div class="page-title"><?php echo e($pageTitle); ?></div>
        </div>
        <ol class="breadcrumb page-breadcrumb pull-right">
            <?php $__currentLoopData = $words; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$word): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li>
                <?php if($key===0): ?>
                <i class="fa fa-home"></i>&nbsp;
                <a class="parent-item" href="<?php echo e(route('home')); ?>">首页</a>&nbsp;
                <?php else: ?>
                    <?php echo e(trans('thumbnail.'.$word)); ?>

                <?php endif; ?>
                <?php if($key!==count($words)-1): ?>
                <i class="fa fa-angle-right"></i>
                <?php endif; ?>
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ol>
    </div>
</div>
<?php /**PATH D:\wamp64\www\etx-t\purfect-teacher-backend\resources\views/reusable_elements/section/thumbnail.blade.php ENDPATH**/ ?>