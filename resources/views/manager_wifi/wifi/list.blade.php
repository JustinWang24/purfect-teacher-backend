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
                        <div class="row table-padding">
                            <div class="col-12">
                                <!--a href="{{ url()->previous() }}" class="btn btn-default">
                                    <i class="fa fa-arrow-circle-left"></i> 返回
                                </a-->&nbsp;
                                <a href="{{ route('manager_wifi.wifi.add') }}" class="btn btn-primary pull-right" id="btn-create-room-from-building">
                                    添加 <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle text-center">
                                <thead>
                                <tr>
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
                                        <td>{{ $val->schools_name }}</td>
                                        <td>{{ $val->campuses_name }}</td>
                                        <td>{{ $val->wifi_name }}</td>
                                        <td>{{ $val->wifi_days }}</td>
                                        <td>{{ $val->wifi_oprice }}</td>
                                        <td>{{ $val->wifi_price }}</td>
                                        <td>{{ $val->wifi_sort }}</td>
                                        <td>{{ $val->status }}</td>
                                        <td class="text-center">
                                            {{ Anchor::Print(['text'=>'编辑','class'=>'btn-edit-room','href'=>route('manager_wifi.wifi.edit',['wifiid'=>$val->wifiid])], Button::TYPE_DEFAULT,'edit') }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {!! $wifiList->fragment('feed')->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection