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
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <form action="{{ route('manager_wifi.wifiIssueType.list') }}" method="get"  id="add-building-form">
                                <div class="pull-left col-2">
                                    <label>类型</label>
                                    <select class="el-input__inner col-10" name="purpose">
                                        <option value="">请选择</option>
                                        @foreach($manageIssueTypesArr as $key=>$val)
                                            <option value="{{$key}}" @if( $key==Request::get('purpose') ) selected="selected" @endif >{{$val}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button class="btn btn-primary">搜索</button>
                            </form>
                            @if(Request::get('type_pid') > 0)
                                <a href="{{ route('manager_wifi.wifiIssueType.list',['type_pid'=>0]) }}" class="btn btn-default">
                                    <i class="fa fa-arrow-circle-left"></i> 返回
                                </a>
                            @endif
                            <a href="{{ route('manager_wifi.wifiIssueType.add',request()->only('typeid','type_pid','purpose')) }}" class="btn btn-primary pull-right" id="btn-create-room-from-building">
                                添加 <i class="fa fa-plus"></i>
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle text-center">
                                <thead>
								<tr>
									<th>序号</th>
									<th>名称</th>
									<th>类型</th>
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
                                        <td>{{ $manageIssueTypesArr[$val->purpose] }}</td>
                                        <td>{{ $val->created_at }}</td>
                                        <td>{{ $val->updated_at }}</td>
                                        <td class="text-center">
                                            @if($val['type_pid'] === 0)
                                                <a href="{{ route('manager_wifi.wifiIssueType.list',['typeid'=>$val->typeid,'type_pid'=>$val->typeid,'purpose'=>$val->purpose]) }}" class="btn btn-primary btn-sm">子菜单</a>
                                            @endif
                                            {{ Anchor::Print(['text'=>'编辑','class'=>'btn-info','href'=>route('manager_wifi.wifiIssueType.edit',['typeid'=>$val->typeid,'type_pid'=>$val->type_pid])], Button::TYPE_DEFAULT,'edit') }}
                                            {{ Anchor::Print(['text'=>'删除','class'=>'btn-danger','href'=>route('manager_wifi.wifiIssueType.delete',['typeid'=>$val->typeid,'type_pid'=>$val->type_pid])], Button::TYPE_DEFAULT,'delete') }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $dataList->appends(Request::all())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection