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
                                <a href="{{ url()->previous() }}" class="btn btn-default">
                                    <i class="fa fa-arrow-circle-left"></i> 返回
                                </a>&nbsp;
                                <!--a href="#" class="btn btn-primary pull-right" id="btn-create-room-from-building">
                                    添加 <i class="fa fa-plus"></i>
                                </a-->
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle text-center">
                                <thead>
                                <tr>
									<th>序号</th>
									<th>报修号</th>
									<th>学校</th>
									<th>地址</th>
									<th>联系人</th>
									<th>电话</th>
									<th>分类</th>
									<th>子类</th>
									<th>故障描述</th>
									<th>申请时间</th>
									<th>状态</th>
									<!--th>操作</th-->
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($dataList as $key=>$val)
                                    <tr  @if($val['status'] === 2) style="color:red" @endif>
                                        <td>{{ $val->issueid }}</td>
                                        <td>{{ $val->trade_sn }}</td>
                                        <td>{{ $val->schools_name }}</td>
                                        <td>{{ $val->addr_detail }}</td>
                                        <td>{{ $val->issue_name }}</td>
                                        <td>{{ $val->issue_mobile }}</td>
                                        <td>{{ $val->typeone_name }}</td>
                                        <td>{{ $val->typetwo_name }}</td>
                                        <td>{{ $val->issue_desc }}</td>
										<td>{{$val->created_at}}</td>
										<td>{{$manageStatusArr[$val['status']]}}</td>
                                        <!--td class="text-center">
                                            {{ Anchor::Print(['text'=>'查看','class'=>'btn-edit-room','href'=>route('manager_wifi.wifiIssue.detail',['issueid'=>$val->issueid])], Button::TYPE_DEFAULT,'detail') }}
                                        </td-->
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