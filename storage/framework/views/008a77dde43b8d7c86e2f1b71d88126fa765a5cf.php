<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
use App\User;
?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card-box">
                <div class="card-head">
                  
                    <header></header>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="row table-padding">
                            <div class="col-12">
                                <!--a href="<?php echo e(url()->previous()); ?>" class="btn btn-default">
                                    <i class="fa fa-arrow-circle-left"></i> 返回
                                </a-->&nbsp;
                                <a href="<?php echo e(route('manager_wifi.wifi.add')); ?>" class="btn btn-primary pull-right" id="btn-create-room-from-building">
                                    添加 <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle text-center">
                                <thead>
                                <tr>
                                    <th width="15%">学校</th>
                                    <th width="15%">校区</th>
                                    <th>类型</th>
                                    <th>天数</th>
                                    <th>原格</th>
                                    <th>现价</th>
                                    <th>排序</th>
                                    <th>状态</th>
                                    <th width="10%">操作</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $__currentLoopData = $wifiList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($val->schools_name); ?></td>
                                        <td><?php echo e($val->campuses_name); ?></td>
                                        <td><?php echo e($val->wifi_name); ?></td>
                                        <td><?php echo e($val->wifi_days); ?></td>
                                        <td><?php echo e($val->wifi_oprice); ?></td>
                                        <td><?php echo e($val->wifi_price); ?></td>
                                        <td><?php echo e($val->wifi_sort); ?></td>
                                        <td><?php echo e($val->status); ?></td>
                                        <td class="text-center">
                                            <?php echo e(Anchor::Print(['text'=>'编辑','class'=>'btn-edit-room','href'=>route('manager_wifi.wifi.edit',['wifiid'=>$val->wifiid])], Button::TYPE_DEFAULT,'edit')); ?>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        <?php echo $wifiList->fragment('feed')->render(); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\wamp64\www\etx-t\purfect-teacher-backend\resources\views/manager_wifi/wifi/list.blade.php ENDPATH**/ ?>