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
                    <header>{{ session('school.name') }} - {{ $parent->name??'' }}</header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-padding col-12">
                            @if(isset($parent) && !empty($parent))
                                <a href="{{ route('school_manager.institute.departments',['uuid'=>$parent->institute->id,'by'=>'institute']) }}" class="btn btn-default">
                                    <i class="fa fa-arrow-circle-left"></i> 返回
                                </a>&nbsp;
                                <a href="{{ route('school_manager.major.add',['uuid'=>$parent->id]) }}" class="btn btn-primary" id="btn-create-major-from-department">
                                    创建新专业 <i class="fa fa-plus"></i>
                                </a>
                            @endif
                            @include('school_manager.school.reusable.nav',['highlight'=>'major'])
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>专业名称</th>
                                    <th>招生计划</th>
                                    <th>班级数</th>
                                    <th>教职工数</th>
                                    <th>在校学生数</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($majors as $index=>$major)
                                    <tr>
                                        <td>{{ $index+1 }}</td>
                                        <td>
                                            {{ $major->name }}
                                        </td>
                                        <td>
                                            @if($major->plans)
                                                @foreach($major->plans as $plan)
                                                    <a target="_blank" href="{{ route('teacher.planRecruit.list',['plan'=>$plan->id]) }}">{{ $plan->title }}: {{ _printDate($plan->start_at) }}开始招收{{ $plan->seats }}人</a>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a class="anchor-grades-counter" href="{{ route('school_manager.major.grades',['uuid'=>$major->id,'by'=>'major']) }}">{{ count($major->grades) }}</a>
                                        </td>
                                        <td class="text-center">
                                            <a class="employees-counter" href="{{ route('school_manager.major.users',['type'=>User::TYPE_EMPLOYEE,'by'=>'major','uuid'=>$major->id]) }}">{{ $major->employeesCount() }}</a>
                                        </td>
                                        <td class="text-center">
                                            <a class="students-counter" href="{{ route('school_manager.major.users',['type'=>User::TYPE_STUDENT,'by'=>'major','uuid'=>$major->id]) }}">{{ $major->studentsCount() }}</a>
                                        </td>
                                        <td class="text-center">
                                            {{ Anchor::Print(['text'=>'编辑','class'=>'btn-edit-major','href'=>route('school_manager.major.edit',['uuid'=>$major->id])], Button::TYPE_DEFAULT,'edit') }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            @if(!isset($parent))
                                {{ $majors->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
