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
                    <header></header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <form action="{{ route('manager_affiche.group.group_pending_list') }}" method="get"  id="add-building-form">
                                <div class="pull-left col-2">
                                    <label>学校</label>
                                    <select id="cityid" class="el-input__inner col-10" name="school_id"></select>
                                </div>
                                <div class="pull-left col-2">
                                    <label>类型</label>
                                    <select id="cityid" class="el-input__inner col-10" name="group_typeid">
                                        <option value="">-请选择-</option>
                                        @foreach($groupTypeIdArr as $key=>$val)
                                        <option value="{{ $key }}" @if( Request::get('group_typeid') == $key ) selected @endif >{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="pull-left col-2">
                                    <label>状态</label>
                                    <select id="cityid" class="el-input__inner col-10" name="status">
                                        <option value="">-请选择-</option>
                                        @foreach($groupStatusArr as $key=>$val)
                                            @if(!in_array($key,[1]))
                                            <option value="{{ $key }}" @if( Request::get('status') == $key ) selected @endif >{{ strip_tags($val) }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="pull-left col-3">
                                    <label>关键词</label>
                                    <input type="text" class="el-input__inner col-10" value="{{ Request::get('keywords') }}" placeholder="手机号" name="keywords">
                                </div>
                                <button class="btn btn-primary">搜索</button>
                            </form>
                            <!--a href="{{ route('manager_wifi.wifi.add') }}" class="btn btn-primary pull-right" id="btn-create-room-from-building">
                                添加 <i class="fa fa-plus"></i>
                            </a-->
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle text-center">
                                <thead>
                                <tr>
                                    <th>序号</th>
                                    <th>学校</th>
                                    <th>手机号</th>
                                    <th>昵称</th>
                                    <th>姓名</th>
                                    <th>名称</th>
                                    <th>描述</th>
                                    <th>人数</th>
                                    <th>申请时间</th>
                                    <th>审核时间</th>
                                    <th>状态</th>
                                    <th width="10%">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($dataList as $key=>$val)
                                    <tr>
                                        <td>{{ $val->groupid }}</td>
                                        <td>{{ $val->school->name }}</td>
                                        <td>{{ $val->user->mobile }}</td>
                                        <td>{{ $val->name }}</td>
                                        <td>{{ $val->nice_name }}</td>
                                        <td> {{ str_limit($val->group_title, 10, '...') }}</td>
                                        <td> {{ str_limit($val->group_content, 20, '...') }}</td>
                                        <td>{{ $val->group_number }}人</td>
                                        <td>{{ $val->created_at }}</td>
                                        <td>{{ $val->group_time1 }}</td>
                                        <td>{{ $groupStatusArr[$val->status] }}</td>
                                        <td class="text-center">

                                            {{ Anchor::Print(['text'=>($val->status == -1 ? '审核' : '查看'),'class'=>'btn btn-primary','href'=>route('manager_affiche.group.group_one',['groupid'=>$val->groupid])], Button::TYPE_DEFAULT,'') }}
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
<script src="{{ route('manager_wifi.WifiApi.get_school_campus') }}" charset="UTF-8"></script>
<script>
    window.onload=function() {
        showLocation({{ Request::get('school_id')?:0 }},{{ Request::get('campus_id')?:0 }});
    }
</script>
@endsection
