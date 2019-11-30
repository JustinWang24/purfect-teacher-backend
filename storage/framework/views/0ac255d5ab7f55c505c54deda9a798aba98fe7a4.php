<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-sm-10 col-md-12 col-xl-12">
            <div class="card-box">
                <div class="card-head">
                    <header>修改</header>
                </div>
                <div class="card-body">
                    <form action="" method="post"  id="add-building-form">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="infos[typeid]" value="<?php echo e($dataOne['typeid']); ?>" id="building-id-input">
                        <div class="form-group">
                            <label for="building-name-input">名称</label>
                            <input required type="text" class="form-control" id="building-name-input" value="<?php echo e($dataOne['type_name']); ?>" placeholder="例如：10" name="infos[type_name]">
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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\wamp64\www\etx-t\purfect-teacher-backend\resources\views/manager_wifi/wifiIssueType/edit.blade.php ENDPATH**/ ?>