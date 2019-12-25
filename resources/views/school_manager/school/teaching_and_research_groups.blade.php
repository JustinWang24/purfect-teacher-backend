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
                <div class="card-body">
                    <div class="row">
                        <div class="table-padding col-12">
                            <a href="{{ route('school_manager.organizations.teaching-and-research-group-add') }}" class="btn btn-primary" id="btn-create-room-from-building">
                                添加教研组 <i class="fa fa-plus"></i>
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>类型</th>
                                    <th>名称</th>
                                    <th>组长</th>
                                    <th>成员</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($groups as $index=>$group)
                                    <tr>
                                        <td>{{ $index+1 }}</td>
                                        <td>{{ $group->type }}</td>
                                        <td>{{ $group->name }}</td>
                                        <td>{{ $group->user_name }}</td>
                                        <td>
                                            @foreach($group->members as $member)
{{ $member->user_name }}&nbsp;
                                            @endforeach
                                        </td>
                                        <td class="text-center">
                                            {{ Anchor::Print(['text'=>'管理组员','class'=>'btn-edit-room','href'=>route('school_manager.organizations.teaching-and-research-group-members',['uuid'=>$group->id])], Button::TYPE_DEFAULT,'edit') }}
                                            {{ Anchor::Print(['text'=>'编辑','class'=>'btn-edit-room','href'=>route('school_manager.organizations.teaching-and-research-group-edit',['uuid'=>$group->id])], Button::TYPE_DEFAULT,'edit') }}
                                            {{ Anchor::Print(['text'=>'删除','class'=>'btn-delete-room btn-need-confirm','href'=>route('school_manager.organizations.teaching-and-research-group-delete',['uuid'=>$group->id])], Button::TYPE_DANGER,'trash') }}
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
