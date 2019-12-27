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
								<form action="{{ route('manager_wifi.wifiIssueComment.list') }}" method="get"  id="add-building-form">
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
										<input type="text" class="el-input__inner col-10" value="{{ Request::get('keywords') }}" placeholder="电话" name="keywords">
									</div>						
									<button class="btn btn-primary">搜索</button>
								</form>
                            </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle text-center">
                                <thead>
                                <tr>
									<th>序号</th>
									<th>报修号</th>
									<th>学校</th>
                                    <th>地址</th>
									<th>类型</th>
									<th>姓名</th>
									<th>电话</th>
									<th>故障描述</th>
									<th>服务态度</th>
									<th>工作效率</th>
									<th>满意度</th>
									<th>评论时间</th>
									<th>操作</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($dataList as $key=>$val)
                                    <tr>
                                        <td>{{ $val->commentid }}</td>
                                        <td>{{ $val->trade_sn }}</td>
                                        <td>{{ $val->schools_name }}</td>
                                        <td>{{ $val->addr_detail }}</td>
                                        <td>{{ $val->typeone_name }}({{ $val->typetwo_name }})</td>
                                        <td>{{ $val->issue_name }}</td>
                                        <td>{{ $val->issue_mobile }}</td>
										<td>{{ $val->issue_desc }}</td>
										<td>{{ $val->comment_service }}</td>
										<td>{{ $val->comment_worders }}</td>
										<td>{{ $val->comment_efficiency }}</td>
										<td>{{ $val->created_at }}</td>
                                        <td class="text-center">
                                            {{ Anchor::Print(['text'=>'查看','class'=>'btn-edit-room','href'=>route('manager_wifi.wifiIssueComment.detail',['commentid'=>$val->commentid])], Button::TYPE_DEFAULT,'detail') }}
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
<script>
window.onload=function() {
    showLocation({{ Request::get('school_id')?:0 }},{{ Request::get('campus_id')?:0 }});
}
</script>
@endsection