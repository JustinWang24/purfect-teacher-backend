<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
use App\User;
?>
@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-4">
            @if(count($todayVisitors) === 0)
                <div class="card">
                    <div class="card-head">
                        <header class="text-danger">今日无访客</header>
                    </div>
                </div>
            @endif
            @foreach($todayVisitors as $todayVisitor)
                <div class="card">
                    <div class="card-head">
                        <header>{{ $todayVisitor->name }}</header>
                    </div>
                    <div class="card-body">
                        <p>邀请人: {{ $todayVisitor->invitedBy->name }}</p>
                        <p>事宜: {{ $todayVisitor->reason }}</p>
                        @if($todayVisitor->vehicle_license)
                            <p>车辆: {{ $todayVisitor->vehicle_license }}</p>
                        @endif
                        <p>预约时间: {{ _printDate($todayVisitor->scheduled_at) }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="col-8">
            <div class="card">
                <div class="card-head">
                    <header>{{ session('school.name') }} 来访管理</header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>预约时间</th>
                                    <th>访客姓名</th>
                                    <th>联系电话</th>
                                    <th>邀请人</th>
                                    <th>事由</th>
                                    <th>车辆</th>
                                    <th>状态</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($visitors as $index=>$visitor)
                                    <tr>
                                        <td>{{ _printDate($visitor->scheduled_at) }}</td>
                                        <td>
                                            {{ $visitor->name }}
                                        </td>
                                        <td>
                                            {{ $visitor->mobile }}
                                        </td>
                                        <td>
                                            {{ $visitor->invitedBy->name }}
                                        </td>
                                        <td>
                                            {{ $visitor->reason }}
                                        </td>
                                        <td>
                                            {{ $visitor->vehicle_license }}
                                        </td>
                                        <td>
                                            {{ $visitor->status }}
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
@endsection
