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
                    <!--header>aaaaaaaaa- bbbbbbbbbbb</header-->
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="row table-padding">
                            <div class="col-12">
                                <a href="{{ route('manager_wifi.wifiIssueType.list',['type_pid'=>0]) }}" class="btn btn-default">
                                    <i class="fa fa-arrow-circle-left"></i> 返回
                                </a>&nbsp;
                                <a href="{{ route('manager_wifi.wifiIssueType.add',request()->only('typeid','type_pid')) }}" class="btn btn-primary pull-right" id="btn-create-room-from-building">
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
									<!--th>类型</th-->
									<th>添加时间</th>
									<th>修改时间</th>
                                    <th>管理操作</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($dataList as $key=>$val)
                                    <tr>
                                        <td>{{ $val->typeid }}</td>
                                        <td>{{ $val->type_name }}</td>
                                        <!--td>{{ $val->purpose }}</td-->
                                        <td>{{ $val->created_at }}</td>
                                        <td>{{ $val->updated_at }}</td>
                                        <td class="text-center">
                                            @if($val['type_pid'] === 0)
                                                <a href="{{ route('manager_wifi.wifiIssueType.list',['typeid'=>$val->typeid,'type_pid'=>$val->typeid]) }}" class="btn btn-primary btn-sm">子菜单</a>
                                            @endif
                                            {{ Anchor::Print(['text'=>'编辑','class'=>'btn-edit-room','href'=>route('manager_wifi.wifiIssueType.edit',['typeid'=>$val->typeid,'type_pid'=>$val->typeid])], Button::TYPE_DEFAULT,'edit') }}
                                            {{ Anchor::Print(['text'=>'删除','class'=>'btn-delete-room','href'=>route('manager_wifi.wifiIssueType.delete',['typeid'=>$val->typeid,'type_pid'=>$val->type_pid])], Button::TYPE_DEFAULT,'delete') }}
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