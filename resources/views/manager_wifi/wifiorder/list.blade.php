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
                            <form action="{{ route('manager_wifi.wifiOrder.list') }}" method="get"  id="add-building-form">
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
                                    <input type="text" class="el-input__inner col-10" value="{{ Request::get('keywords') }}" placeholder="手机号" name="keywords">
                                </div>
                                <button class="btn btn-primary">搜索</button>
                            </form>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle text-center">
                                <thead>
                                <tr>
									<th>订单号</th>
									<th>姓名</th>
									<th>手机号</th>
									<th>学校</th>
									<th>类型</th>
									<th>数量</th>
									<th>单价</th>
									<th>总价</th>
									<th>添加时间</th>
									<th>支付时间</th>
									<th>支付方式</th>
									<th>支付状态</th>
                                    <th width="10%">操作</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($dataList as $key=>$val)
                                    <tr>
                                        <td width="10%">{{ $val['trade_sn'] }}</td>
                                        <td>{{ $val['name']?$val['name']:'---'}}</td>
                                        <td>{{ $val['mobile'] }}</td>
                                        <td>{{ $val['school_name'] }}</td>
                                        <td>{{ $val['wifi_name'] }}</td>
                                        <td>{{ $val['order_number'] }}</td>
										<td>{{$val['order_unitprice']}}</td>
										<td>{{$val['order_totalprice']}}</td>
										<td>{{$val['created_at']}}</td>
										<td>{{$val['pay_time']}}</td>
										<td>{{$paymentidArr[$val['paymentid']]}}</td>
										<td>{{$manageWifiStatusArr[$val['status']]}}</td>
                                        <td class="text-center">
                                            {{ Anchor::Print(['text'=>'详情','class'=>'btn-edit-room','href'=>route('manager_wifi.wifiOrder.detail',['trade_sn'=>$val->trade_sn])], Button::TYPE_DEFAULT,'') }}
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