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
                    <header class="full-width">
                        <span class="pull-left pt-2">{{ $parent->name??session('school.name') }} 教职工列表: (总数: {{ $employees->total() }})</span>
                        <a href="#" class="btn btn-primary pull-right">
                            添加新教职工 <i class="fa fa-plus"></i>
                        </a>
                    </header>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="table-padding col-12">
                            @include('school_manager.school.reusable.nav',['highlight'=>'teacher'])
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>姓名</th>
                                    <th>所在单位</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($employees as $index=>$gradeUser)
                                    @php
/** @var \App\Models\Users\GradeUser $gradeUser */
                                    @endphp
                                    <tr>
                                        <td>{{ $index+1 }}</td>
                                        <td>
                                            {{ $gradeUser->user->name }}
                                        </td>
                                        <td>{{ $gradeUser->workAt() }}</td>
                                        <td class="text-center">
                                            {{ Anchor::Print(['text'=>'档案管理','href'=>route('school_manager.teachers.edit-profile',['uuid'=>$gradeUser->user_id])], Button::TYPE_DEFAULT,'edit') }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            {{ $employees->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection