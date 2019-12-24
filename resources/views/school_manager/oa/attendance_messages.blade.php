<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
use App\User;
?>
@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card">
                <div class="card-head">
                    <header>{{ session('school.name') }} 考勤管理</header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>姓名</th>
                                    <th>补卡日期</th>
                                    <th>补卡时间</th>
                                    <th>类型</th>
                                    <th>补卡理由</th>
                                    <th>审批人</th>
                                    <th>状态</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($messages as $index=>$message)
                                    <tr>
                                        <td>
                                            {{ $message->user->name }}
                                        </td>
                                        <td>
                                            {{$message->attendance_date}}
                                        </td>
                                        <td>
                                            {{ $message->attendance_time }}
                                        </td>
                                        <td>
                                            @if($message->type==1)
                                                上午
                                            @elseif($message->type==2)
                                                下午
                                            @elseif($message->type==3)
                                                全天
                                            @endif
                                        </td>
                                        <td>
                                            {{ $message->content }}
                                        </td>
                                        <td>
                                            {{ $message->manager->name }}
                                        </td>

                                        <td class="text-center">
                                            @if($message->status==1)
                                            {{ Anchor::Print(['text'=>'通过','class'=>'btn-edit-major','href'=>route('school_manager.oa.attendances-accept-messages',['id'=>$message->id])], Button::TYPE_DEFAULT,'edit') }}
                                            {{ Anchor::Print(['text'=>'拒绝','class'=>'btn-edit-major btn-need-confirm','href'=>route('school_manager.oa.attendances-reject-messages',['id'=>$message->id])], Button::TYPE_DEFAULT,'edit') }}
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            {{ $messages->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
