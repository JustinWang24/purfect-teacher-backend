<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>
@extends('layouts.app')
@section('content')
 <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card-box">
                <div class="card-head">
                    <header>用户信息</header>
                </div>
                <div class="card-body">
                    <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="application-user-name">姓名：</label>
                                    {{$dataOne['getUsersOneInfo']->name}}
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="application-user-name">手机号：</label>
                                    {{$dataOne['getUsersOneInfo']->mobile}}
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="application-user-name">学校：</label>
                                    {{$dataOne['getWifiOrdersOneInfo']->school_name}}
                                </div>
                            </div>


                            <div class="col-3">
                                <div class="form-group">
                                    <label for="application-user-name">校区：</label>
                                    {{$dataOne['getWifiOrdersOneInfo']->campuses_name}}
                                </div>
                            </div>

                        </div>
                </div>
            </div>

            <div class="card-box">
                <div class="card-head">
                    <header>订单信息</header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">订单号：</label>
                                {{$dataOne['getWifiOrdersOneInfo']->trade_sn}}
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">类型：</label>
                                {{$dataOne['getWifiOrdersOneInfo']->wifi_name}}
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">数量：</label>
                                {{$dataOne['getWifiOrdersOneInfo']->order_number}}
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">单价：</label>
                                {{$dataOne['getWifiOrdersOneInfo']->order_unitprice}}
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">总价：</label>
                                {{$dataOne['getWifiOrdersOneInfo']->order_totalprice}}
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">添加时间：</label>
                                {{$dataOne['getWifiOrdersOneInfo']->created_at}}
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">支付状态：</label>
                                {{$dataOne['manageWifiStatusArr'][$dataOne['getWifiOrdersOneInfo']->status]}}
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">支付方式：</label>
                                {{$dataOne['getWifiOrdersOneInfo']->payment_name}}
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">支付时间：</label>
                                {{$dataOne['getWifiOrdersOneInfo']->pay_time}}
                            </div>
                        </div>


                    </div>
                </div>
            </div>
           <?php
           Anchor::Print(['text'=>trans('general.return'),'href'=>route('manager_wifi.wifiOrder.list'),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
           ?>
        </div>
 </div>
@endsection
