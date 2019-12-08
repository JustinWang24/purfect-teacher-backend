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
                    <!--header>title1- title2</header-->
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="row table-padding">
                            <div class="col-12">
                                <a href="<?php echo e(url()->previous()); ?>" class="btn btn-default">
                                    <i class="fa fa-arrow-circle-left"></i> 返回
                                </a>&nbsp;
                                <a href="<?php echo e(route('manager_wifi.wifiNotice.add')); ?>" class="btn btn-primary pull-right" id="btn-create-room-from-building">
                                    添加 <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle text-center">
                                <thead>
                                <tr>
									<th>序号</th>
									<th>标题</th>
									<th>排序</th>
									<th>添加时间</th>
									<th>修改时间</th>
									<th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $__currentLoopData = $dataList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
										<td><?php echo e($val['noticeid']); ?></td>
										<td><?php echo e($val['notice_title']); ?></td>
										<td><?php echo e($val['sort']); ?></td>
										<td><?php echo e($val['created_at']); ?></td>
										<td><?php echo e($val['updated_at']); ?></td>
                                        <td class="text-center">
                                            <?php echo e(Anchor::Print(['text'=>'编辑','class'=>'btn-edit-room','href'=>route('manager_wifi.wifiNotice.edit',['noticeid'=>$val->noticeid])], Button::TYPE_DEFAULT,'edit')); ?>

                                            <?php echo e(Anchor::Print(['text'=>'删除','class'=>'btn-edit-room','href'=>route('manager_wifi.wifiNotice.delete',['noticeid'=>$val->noticeid])], Button::TYPE_DEFAULT,'delete')); ?>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        <?php echo $dataList->fragment('feed')->render(); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\wamp64\www\etx-t\purfect-teacher-backend\resources\views/manager_wifi/wifiNotice/list.blade.php ENDPATH**/ ?>