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
                    <!--header>titile1- titile2</header-->
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="row table-padding">
                            <div class="col-12">
                                <!--a href="<?php echo e(url()->previous()); ?>" class="btn btn-default">
                                    <i class="fa fa-arrow-circle-left"></i> 返回
                                </a>&nbsp;
                                <a href="<?php echo e(route('manager_wifi.wifi.add')); ?>" class="btn btn-primary pull-right" id="btn-create-room-from-building">
                                    添加 <i class="fa fa-plus"></i>
                                </a-->
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle text-center">
                                <thead>
                                <tr>
									<th>订单号</th>
									<th>姓名</th>
									<th>手机号</th>
									<th>学校</th>
									<th>类型</th>
									<th>数量</th>
									<th>单价</th>
									<th>总价</th>
									<th>添加时间</th>
									<th>支付时间</th>
									<th>支付方式</th>
									<th>支付状态</th>
                                    <!--th width="10%">操作</th-->
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $__currentLoopData = $dataList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td width="10%"><?php echo e($val['trade_sn']); ?></td>
                                        <td><?php echo e($val['name']?$val['name']:'---'); ?></td>
                                        <td><?php echo e($val['mobile']); ?></td>
                                        <td>枣职</td>
                                        <td><?php echo e($val['wifi_name']); ?></td>
                                        <td><?php echo e($val['order_number']); ?></td>
										<td><?php echo e($val['order_unitprice']); ?></td>
										<td><?php echo e($val['order_totalprice']); ?></td>
										<td><?php echo e($val['created_at']); ?></td>
										<td><?php echo e($val['pay_time']); ?></td>
										<td><?php echo e($paymentidArr[$val['paymentid']]); ?></td>
										<td><?php echo e($manageWifiStatusArr[$val['status']]); ?></td>
                                        <!--td class="text-center">
                                            <?php echo e(Anchor::Print(['text'=>'详情','class'=>'btn-edit-room','href'=>route('manager_wifi.wifiOrder.detail',['wifiid'=>$val->wifiid])], Button::TYPE_DEFAULT,'')); ?>

                                        </td-->
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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\wamp64\www\etx-t\purfect-teacher-backend\resources\views/manager_wifi/wifiOrder/list.blade.php ENDPATH**/ ?>