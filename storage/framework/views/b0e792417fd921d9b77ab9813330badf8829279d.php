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
                  
                    <header>aaaaaaaaa- bbbbbbbbbbb</header>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="row table-padding">
                            <div class="col-12">
                                <a href="<?php echo e(url()->previous()); ?>" class="btn btn-default">
                                    <i class="fa fa-arrow-circle-left"></i> 返回
                                </a>&nbsp;
                                <a href="<?php echo e(route('manager_wifi.wifi.add')); ?>" class="btn btn-primary pull-right" id="btn-create-room-from-building">
                                    添加 <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
								<tr>
									<th>序号</th>
									<th>名称</th>
									<th>类型</th>
									<th>添加时间</th>
									<th>修改时间</th>
                                    <th style="width: 300px;">管理操作</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $__currentLoopData = $dataList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($val->typeid); ?></td>
                                        <td><?php echo e($val->type_name); ?></td>
                                        <td><?php echo e($val->purpose); ?></td>
                                        <td><?php echo e($val->create_time); ?></td>
                                        <td><?php echo e($val->update_time); ?></td>
                                        <td class="text-center">
											<eq name="vo.type_pid" value='0'>
												<a href="{:U('list',array('typeid'=>$vo['typeid']))}" class="btn btn-primary btn-sm">子菜单</a>
											</eq>
                                            <?php echo e(Anchor::Print(['text'=>'编辑','class'=>'btn-edit-room','href'=>route('manager_wifi.wifiissuetype.edit',['typeid'=>$val->typeid])], Button::TYPE_DEFAULT,'edit')); ?>

                                            <?php echo e(Anchor::Print(['text'=>'删除','class'=>'btn-edit-room','href'=>route('manager_wifi.wifiissuetype.delete',['typeid'=>$val->typeid])], Button::TYPE_DEFAULT,'edit')); ?>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\wamp64\www\etx-t\purfect-teacher-backend\resources\views/manager_wifi/wifiissuetype/list.blade.php ENDPATH**/ ?>