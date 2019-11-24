<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
use App\User;
?>
@extends('layouts.app')
@section('content')
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
                                <a href="{{ url()->previous() }}" class="btn btn-default">
                                    <i class="fa fa-arrow-circle-left"></i> 返回
                                </a>&nbsp;
                                <a href="{{ route('manager_wifi.wifi.add') }}" class="btn btn-primary pull-right" id="btn-create-room-from-building">
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
                                @foreach($dataList as $key=>$val)
                                    <tr>
                                        <td>{{ $val->typeid }}</td>
                                        <td>{{ $val->type_name }}</td>
                                        <td>{{ $val->purpose }}</td>
                                        <td>{{ $val->create_time }}</td>
                                        <td>{{ $val->update_time }}</td>
                                        <td class="text-center">
											<eq name="vo.type_pid" value='0'>
												<a href="{:U('list',array('typeid'=>$vo['typeid']))}" class="btn btn-primary btn-sm">子菜单</a>
											</eq>
                                            {{ Anchor::Print(['text'=>'编辑','class'=>'btn-edit-room','href'=>route('manager_wifi.wifiissuetype.edit',['typeid'=>$val->typeid])], Button::TYPE_DEFAULT,'edit') }}
                                            {{ Anchor::Print(['text'=>'删除','class'=>'btn-edit-room','href'=>route('manager_wifi.wifiissuetype.delete',['typeid'=>$val->typeid])], Button::TYPE_DEFAULT,'edit') }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection