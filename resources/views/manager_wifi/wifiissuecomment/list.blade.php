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
									<th>报修号</th>
									<th>学校</th>
									<th>类型</th>
									<th>地址</th>
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
                                        <td>{{ $val->school_name }}</td>
                                        <td>{{ $val->typeone_name }}({{ $val->typetwo_name }})</td>
                                        <td>{{ $val->issue_name }}</td>
                                        <td>{{ $val->issue_mobile }}</td>
										<td>{$vo.issue_desc}</td>
										<td>{$vo.comment_service}</td>
										<td>{$vo.comment_worders}</td>
										<td>{$vo.comment_efficiency}</td>
										<td>{$vo.create_time}</td>
                                        <td class="text-center">
                                            {{ Anchor::Print(['text'=>'查看','class'=>'btn-edit-room','href'=>route('manager_wifi.wifiissuecomment.detail',['commentid'=>$val->commentid])], Button::TYPE_DEFAULT,'edit') }}
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