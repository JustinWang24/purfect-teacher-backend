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
                    <!--header>titile1- titile2</header-->
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <form action="{{ route('welcome_manager.welcomeReport.tuitionfee_list',['page' => Request::get('page') ]) }}" method="get"  id="add-building-form">
                                <div class="pull-left col-3">
                                    <label>关键词</label>
                                    <input type="text" class="el-input__inner col-10" value="{{ Request::get('keywords') }}" placeholder="姓名,身份证号码" name="keywords">
                                </div>
                                <button class="btn btn-primary">搜索</button>
                            </form>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle text-center">
                                <thead>
                                <tr>
									<th>序号</th>
                                    <th>姓名</th>
                                    <th>性别</th>
                                    <th>出生日期</th>
                                    <th>民族</th>
                                    <th>政治面貌</th>
                                    <th>学号</th>
                                    <th>身份证</th>
                                    <th>手机号</th>
                                    <th>学院</th>
                                    <th>年级</th>
                                    <th>专业</th>
                                    <th width="10%">操作</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($dataList as $key=>$val)
                                    @php
                                        $info = json_decode($val->steps_1_str);
                                    @endphp
                                    <tr>
                                        <td width="10%">{{ $val->configid }}</td>
                                        <td>{{ isset($info->user_name)?$info->user_name:'---'}}</td>
                                        <td>{{ isset($info->gender)?$info->gender:'---' }}</td>
                                        <td>{{ isset($info->birthday)?$info->birthday:'---' }}</td>
                                        <td>{{ isset($info->nation_name)?$info->nation_name:'---' }}</td>
                                        <td>{{ isset($info->political_name)?$info->political_name:'---' }}</td>
                                        <td>{{ isset($info->serial_number)?$info->serial_number:'---' }}</td>
                                        <td>{{ isset($info->id_number)?$info->id_number:'---' }}</td>
                                        <td>手机号</td>
                                        <td>{{ isset($info->institute_name)?$info->institute_name:'---' }}</td>
                                        <td>年级</td>
                                        <td>{{ isset($info->major_name)?$info->major_name:'---' }}</td>
                                        <td class="text-center">
                                            {{ Anchor::Print(['text'=>'详情','class'=>'btn btn-round btn-default btn btn-primary','href'=>route('welcome_manager.welcomeReport.cost_detail',['id'=>$val['uuid'],'typeid'=>Request::get('typeid'),'index'=>Request::get('index')])], Button::TYPE_DEFAULT,'') }}
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
