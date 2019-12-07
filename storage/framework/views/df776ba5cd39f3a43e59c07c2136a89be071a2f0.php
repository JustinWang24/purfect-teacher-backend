<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-sm-10 col-md-12 col-xl-12">
            <div class="card-box">
                <div class="card-head">
                    <header>添加</header>
                </div>
                <div class="card-body">
                    <form action="" method="post"  id="add-building-form">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="dataOne[noticeid]" value="<?php echo e($dataOne['noticeid']); ?>" id="building-id-input">
                       
                        <div class="form-group">
                            <label for="building-name-input">标题</label>
                            <input required type="text" class="form-control" id="building-name-input" value="<?php echo e($dataOne['notice_title']); ?>" placeholder="" name="infos[notice_title]">
                        </div>
                        <div class="form-group">
                            <label for="building-name-input">内容</label>
                            <input required type="text" class="form-control" id="building-name-input" value="<?php echo e($dataOne['notice_content']); ?>" placeholder="" name="infos[notice_content]">
                        </div>
                        <div class="form-group">
                            <label for="building-name-input">排序</label>
                            <input required type="text" class="form-control" id="building-name-input" value="<?php echo e($dataOne['sort']); ?>" placeholder="例如：1000" name="infos[sort]">
                        </div>
                       <?php
                       Button::Print(['id'=>'btn-create-building','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                       ?>&nbsp;
                       <?php
                       Anchor::Print(['text'=>trans('general.return'),'href'=>url()->previous(),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                       ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\wamp64\www\etx-t\purfect-teacher-backend\resources\views/manager_wifi/wifinotice/edit.blade.php ENDPATH**/ ?>