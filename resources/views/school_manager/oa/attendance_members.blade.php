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
                        <form action="{{ route('school_manager.oa.attendances-members',['id'=>$groupId]) }}" method="post" id="edit-group-form">
                            @csrf
                            <div class="form-group">
                                <input type="text" class="form-control" id="building-name-input" value="" placeholder="搜索" name="search">
                            </div>
                        </form>


                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>姓名</th>
                                    <th>身份</th>
                                    <th>设备标识</th>
                                    <th>状态</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($members as $index=>$member)
                                    <tr>
                                        <td>
                                            {{ $member->user->name }}
                                        </td>
                                        <td>
                                            @if($member->status == 2) 管理员
                                            @else 组员
                                            @endif
                                        </td>
                                        <td>
                                            {{ $member->mac_address }}
                                        </td>

                                        <td class="text-center">
                                            {{ Anchor::Print(['text'=>'添加组员','class'=>'btn-edit-major','href'=>route('school_manager.oa.attendances-add-member',['id'=>$member->user_id,'group'=>$groupId])], Button::TYPE_DEFAULT,'edit') }}
                                            {{ Anchor::Print(['text'=>'添加管理员','class'=>'btn-edit-major btn-need-confirm','href'=>route('school_manager.oa.attendances-add-member',['id'=>$member->user_id,'group'=>$groupId,'type'=>2])], Button::TYPE_DEFAULT,'edit') }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            {{ $members->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
