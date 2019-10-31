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
                    <header>系名: {{ session('school.name') }} - {{ $parent->name }}</header>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="row table-padding">
                            <div class="col-12">
                                <a href="{{ route('school_manager.institute.departments',['uuid'=>$parent->institute->id,'by'=>'institute']) }}" class="btn btn-default">
                                    <i class="fa fa-arrow-circle-left"></i> 返回
                                </a>&nbsp;
                                <a href="{{ route('school_manager.major.add',['uuid'=>$parent->id]) }}" class="btn btn-primary pull-right" id="btn-create-major-from-department">
                                    创建新专业 <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>专业名称</th>
                                    <th>自主招生</th>
                                    <th>招生人数</th>
                                    <th>班级数</th>
                                    <th>教职工数</th>
                                    <th>学生数</th>
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
                                            @if($major->open)
                                                <span class="text-primary">已经开放</span>
                                            @else
                                                <span class="text-danger">已经停止</span>
                                            @endif
                                        </td>
                                        <td>{{ $major->seats }}人</td>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection