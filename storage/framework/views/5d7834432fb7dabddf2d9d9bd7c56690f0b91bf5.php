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
                    <form action="<?php echo e(route('manager_wifi.wifi.add')); ?>" method="post"  id="add-building-form">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label for="building-name-input">学校</label>
                            <input required type="text" class="form-control" id="building-name-input" value="" placeholder="" name="infos[school_id]">
                        </div>
                        <div class="form-group">
                            <label for="building-name-input">分校</label>
                            <input required type="text" class="form-control" id="building-name-input" value="" placeholder="" name="infos[campus_id]">
                        </div>
                        <div class="form-group">
                            <!--类型(1:无线,2:有线)-->
                            <label for="school-name-input">模式</label>
                            <select class="form-control" name="infos[mode]"  required>
                                <option value="">请选择</option>
                                <option value="1">无线</option>
                                <option value="2">有线</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="school-name-input">类型</label>
                            <select class="form-control" name="infos[wifi_type]"  required>
                                <option value="">请选择</option>
                                <?php $__currentLoopData = $manageWifiArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($val['id']); ?>"><?php echo e($val['name']); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="building-name-input">天数</label>
                            <input required type="text" class="form-control" id="building-name-input" value="" placeholder="例如：10" name="infos[wifi_days]">
                        </div>
                        <div class="form-group">
                            <label for="building-name-input">原始价格</label>
                            <input required type="text" class="form-control" id="building-name-input" value="" placeholder="例如：100" name="infos[wifi_oprice]">
                        </div>
                        <div class="form-group">
                            <label for="building-name-input">支付价格</label>
                            <input required type="text" class="form-control" id="building-name-input" value="" placeholder="例如：80" name="infos[wifi_price]">
                        </div>
                        <div class="form-group">
                            <label for="building-name-input">排序</label>
                            <input required type="text" class="form-control" id="building-name-input" value="" placeholder="例如：1000" name="infos[wifi_sort]">
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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\wamp64\www\etx-t\purfect-teacher-backend\resources\views/manager_wifi/wifi/add.blade.php ENDPATH**/ ?>