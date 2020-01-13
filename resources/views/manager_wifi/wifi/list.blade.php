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
                            <form action="{{ route('manager_wifi.wifi.list') }}" method="get"  id="add-building-form">
                                <div class="pull-left col-2">
                                    <label>学校</label>
                                    <select id="cityid" class="el-input__inner col-10" name="school_id"></select>
                                </div>
                                <div class="pull-left col-2">
                                    <label>校区</label>
                                    <select id="countryid" class="el-input__inner col-10" name="campus_id"></select>
                                </div>
                                <button class="btn btn-primary">搜索</button>
                            </form>
                            <a href="{{ route('manager_wifi.wifi.add') }}" class="btn btn-primary pull-right" id="btn-create-room-from-building">
                                添加 <i class="fa fa-plus"></i>
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle text-center">
                                <thead>
                                <tr>
                                    <th>排序</th>
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
                                @foreach($wifiList as $key=>$val)
                                    <tr>
                                        <td>{{ $val->wifi_sort }}</td>
                                        <td>{{ $val->schools_name }}</td>
                                        <td>{{ $val->campuses_name }}</td>
                                        <td>{{ $val->wifi_name }}</td>
                                        <td>{{ $val->wifi_days }}</td>
                                        <td>{{ $val->wifi_oprice }}</td>
                                        <td>{{ $val->wifi_price }}</td>
                                        <td>{{ $val->wifi_sort }}</td>
                                        <td>{{ $val->status }}</td>
                                        <td class="text-center">
                                            {{ Anchor::Print(['text'=>'编辑','class'=>'btn-edit-room btn-info','href'=>route('manager_wifi.wifi.edit',['wifiid'=>$val->wifiid])], Button::TYPE_DEFAULT,'edit') }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $wifiList->appends(Request::all())->links() }}
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