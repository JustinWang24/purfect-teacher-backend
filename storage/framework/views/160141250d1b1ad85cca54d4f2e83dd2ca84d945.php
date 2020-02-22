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
                        <div class="col-12 mb-2">
                            <form action="<?php echo e(route('manager_wifi.wifiOrder.list')); ?>" method="get"  id="add-building-form">
                                <div class="pull-left col-2">
                                    <label>学校</label>
                                    <select id="cityid" class="el-input__inner col-10" name="school_id"></select>
                                </div>

                                <div class="pull-left col-2">
                                    <label>校区</label>
                                    <select id="countryid" class="el-input__inner col-10" name="campus_id"></select>
                                </div>

                                <div class="pull-left col-3">
                                    <label>关键词</label>
                                    <input type="text" class="el-input__inner col-10" value="<?php echo e(Request::get('keywords')); ?>" placeholder="手机号" name="keywords">
                                </div>
                                <button class="btn btn-primary">搜索</button>
                            </form>
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
                                    <th width="10%">操作</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $__currentLoopData = $dataList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td width="10%"><?php echo e($val['trade_sn']); ?></td>
                                        <td><?php echo e($val['name']?$val['name']:'---'); ?></td>
                                        <td><?php echo e($val['mobile']); ?></td>
                                        <td><?php echo e($val['school_name']); ?></td>
                                        <td><?php echo e($val['wifi_name']); ?></td>
                                        <td><?php echo e($val['order_number']); ?></td>
										<td><?php echo e($val['order_unitprice']); ?></td>
										<td><?php echo e($val['order_totalprice']); ?></td>
										<td><?php echo e($val['created_at']); ?></td>
										<td><?php echo e($val['pay_time']); ?></td>
										<td><?php echo e($paymentidArr[$val['paymentid']]); ?></td>
										<td><?php echo e($manageWifiStatusArr[$val['status']]); ?></td>
                                        <td class="text-center">
                                            <?php echo e(Anchor::Print(['text'=>'详情','class'=>'btn btn-primary','href'=>route('manager_wifi.wifiOrder.detail',['trade_sn'=>$val->trade_sn])], Button::TYPE_DEFAULT,'')); ?>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        <?php echo e($dataList->appends(Request::all())->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="<?php echo e(route('manager_wifi.WifiApi.get_school_campus')); ?>" charset="UTF-8"></script>	
<script>
    window.onload=function() {
        showLocation(<?php echo e(Request::get('school_id')?:0); ?>,<?php echo e(Request::get('campus_id')?:0); ?>);
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\wamp64\www\etx-t\purfect-teacher-backend\resources\views/manager_wifi/wifiOrder/list.blade.php ENDPATH**/ ?>