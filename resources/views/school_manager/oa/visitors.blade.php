<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
use App\User;
?>
@extends('layouts.app')
@section('content')
<div class="row" style="margin-bottom: 6px;">
        <form action="{{ route('school_manager.oa.visitors-manager') }}" method="get"  id="add-building-form">
            <div class="pull-left col-3">
                <label>开始时间</label>
                <input type="text" class="el-input__inner col-8" value="" placeholder="">
            </div>
            <div class="pull-left col-3">
                <label>截止时间</label>
                <input type="text" class="el-input__inner col-8" value="" placeholder="">
            </div>
            <div class="pull-left col-2">
                <label>状态</label>
                <select id="cityid" class="el-input__inner col-8" name="status">
                    <option value="">-请选择-</option>
                    <!--状态(1:已分享,2:已填写,3:已使用)-->
                    <option value="2" @if( Request::get('status') == 2 ) selected @endif >已邀请</option>
                    <option value="3" @if( Request::get('status') == 3 ) selected @endif >已到访</option>
                    <option value="3" @if( Request::get('status') == 1 ) selected @endif >未到访</option>

                </select>
            </div>
            <div class="pull-left col-3">
                <label>关键词</label>
                <input type="text" class="el-input__inner col-8" value="{{ Request::get('keywords') }}" placeholder="申请人 / 邀请人" name="keywords">
            </div>
            <button class="btn btn-primary">搜索</button>
        </form>
    </div>
    <div class="row">
        <div class="col-4">
            <div class="card-container">
                <div class="card-item">
                    <p class="card-item-1">今日预约</p>
                    <p>{{ $todayVisitorsCount['count2'] }}</p>
                </div>
                <div class="card-item">
                    <p class="card-item-2">已到访</p>
                    <p>{{ $todayVisitorsCount['count3'] }}</p>
                </div>
                <div class="card-item">
                    <p class="card-item-3">未到访</p>
                    <p>{{ $todayVisitorsCount['count1'] }}</p>
                </div>
            </div>

            <div class="card">
            @foreach($todayVisitors as $vo)
                    <div class="card-body">
                        <p class="card-body-p"><span><strong>邀请人：</strong>{{ $vo->uname }}</span> <span>{{ $vo->created_at }}</span> </p>
                        <p class="card-body-p"><span><strong>来访人：</strong>{{ $vo->name }}</span> <span>{{ count(json_decode($vo->visitors_json1,true)) }}人</span> </p>
                        <p class="card-body-p"><span><strong>联系电话：</strong>{{ $vo->mobile }}</span> <span><strong>预约时间：</strong>{{ $vo->scheduled_at }}</span> </p>
                    </div>
            @endforeach
           </div>
        </div>
        <style>
            .card-body{
                margin-left: 5px;
                margin-right: 5px;
                border-bottom:1px solid #ccc;
            }
        </style>
        <div class="col-8">
            <div class="card" style="margin-top: 0px;!important;">
                <div class="card-head">
                    <header>{{ session('school.name') }} 来访列表</header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>预约时间</th>
                                    <th>邀请人</th>
                                    <th>申请人</th>
                                    <th>联系电话</th>
                                    <th>来访人员</th>
                                    <th>车辆</th>
                                    <th>事由</th>
                                    <th>状态</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($visitors as $index=>$visitor)
                                    <tr>
                                        <td width="10%">{{ _printDate($visitor->scheduled_at) }}</td>
                                        <td>
                                            {{ $visitor->uname }}
                                        </td>
                                        <td>
                                            {{ $visitor->name }}
                                        </td>
                                        <td>
                                            {{ $visitor->mobile }}
                                        </td>
                                        <td>
                                            @php
                                            $visitors_json1Arr = json_decode($visitor->visitors_json1,true);
                                            @endphp
                                            @foreach($visitors_json1Arr as $userVal)
                                                <p>{{ $userVal['name'] }} / {{ $userVal['mobile'] }}</p>
                                            @endforeach
                                        </td>
                                        <td>
                                            @php
                                                $visitors_json2Arr = json_decode($visitor->visitors_json2,true);
                                            @endphp
                                            @foreach($visitors_json2Arr as $userVal)
                                                <p>{{ $userVal['title'] }}</p>
                                            @endforeach
                                        </td>
                                        <td  width="20%">
                                            {{ $visitor->reason }}
                                        </td>
                                        <td>
                                            {{ $visitor->status === \App\Models\OA\Visitor::NOT_VISITED ? '未到访' : '已到访' }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $visitors->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .card-container{
            display: -webkit-flex;
            display: flex;
            -webkit-justify-content: space-between;
            justify-content: space-between;
            background-color: #ffffff;
        }
        .card-item {
            text-align: center;
            width: 100px;
            height: 100px;
            padding-top: 25px;
            margin: 10px;
        }
        .card-item p:first-child{
            font-weight: bold;
        }
        .card-item p:first-child{
            font-size: 20px;
            font-weight: bold;
        }
        .card-item-1 {
            color: dodgerblue;
        }
        .card-item-2 {
            color: forestgreen;
        }
        .card-item-3 {
            color: #C4C4C4;
        }
        .card-body-p {
            font-size: 13px;
        }
        .card-body-p span:nth-child(2) {
            display: inline-block;
            float: right;
        }
    </style>
@endsection
