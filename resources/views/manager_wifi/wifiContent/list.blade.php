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
                    <!--header>title1- title2</header-->
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <form action="{{ route('manager_wifi.wifiContent.list') }}" method="get"  id="add-building-form">
                                <div class="pull-left col-2">
                                    <label>学校</label>
                                    <select id="cityid" class="el-input__inner col-10" name="school_id"></select>
                                </div>

                                <div class="pull-left col-2">
                                    <label>校区</label>
                                    <select id="countryid" class="el-input__inner col-10" name="campus_id"></select>
                                </div>
                                <button class="btn btn-primary">搜索</button>
                                <a href="{{ route('manager_wifi.wifiContent.add') }}" class="btn btn-primary pull-right" id="btn-create-room-from-building">
                                    添加 <i class="fa fa-plus"></i>
                                </a>
                            </form>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle text-center">
                                <thead>
                                <tr>
									<th>序号</th>
									<th>学校</th>
									<th>校区</th>
									<th>类型</th>
									<th>添加时间</th>
									<th>修改时间</th>
									<th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($dataList as $key=>$val)
                                    <tr>
										<td>{{$val['contentid']}}</td>
										<td>{{$val['schools_name']}}</td>
										<td>{{$val['campuses_name']}}</td>
										<td>{{$wifiContentsTypeArr[$val['typeid']]}}</td>
										<td>{{$val['created_at']}}</td>
										<td>{{$val['updated_at']}}</td>
                                        <td class="text-center">
                                            {{ Anchor::Print(['text'=>'编辑','class'=>'btn-edit-room btn-info','href'=>route('manager_wifi.wifiContent.edit',['contentid'=>$val->contentid])], Button::TYPE_DEFAULT,'edit') }}
                                            {{ Anchor::Print(['text'=>'删除','class'=>'btn-delete-room btn-danger', 'href'=>route('manager_wifi.wifiContent.delete',['contentid'=>$val->contentid])], Button::TYPE_DEFAULT,'delete') }}
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