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
									<th>标题</th>
									<th>排序</th>
									<th>添加时间</th>
									<th>修改时间</th>
									<th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($dataList as $key=>$val)
                                    <tr>
										<td>{{$val['noticeid']}}</td>
										<td>{{$val['notice_title']}}</td>
										<td>{{$val['sort']}}</td>
										<td>{{$val['created_at']}}</td>
										<td>{{$val['updated_at']}}</td>
                                        <td class="text-center">
                                            {{ Anchor::Print(['text'=>'编辑','class'=>'btn-edit-room','href'=>route('manager_wifi.wifinotice.edit',['noticeid'=>$val->noticeid])], Button::TYPE_DEFAULT,'edit') }}
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