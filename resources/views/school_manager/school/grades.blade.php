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
                    <header>专业名: {{ session('school.name') }} - {{ $parent->name }}</header>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="row table-padding">
                            <div class="col-12">
                                <a href="{{ route('school_manager.school.view') }}" class="btn btn-default">
                                    返回 <i class="fa fa-arrow-circle-left"></i>
                                </a>&nbsp;
                                <a href="{{ route('school_manager.grade.add',['uuid'=>$parent->id]) }}" class="btn btn-primary pull-right">
                                    创建班级 <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>班级名称</th>
                                    <th class="text-center">学生数</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($grades as $index=>$grade)
                                    <tr>
                                        <td>{{ $index+1 }}</td>
                                        <td>
                                            {{ $grade->name }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('school_manager.grade.users',['type'=>User::TYPE_STUDENT,'by'=>'grade','uuid'=>$grade->id]) }}">{{ $grade->studentsCount() }}</a>
                                        </td>
                                        <td class="text-center">
                                            {{ Anchor::Print(['text'=>'编辑','href'=>route('school_manager.grade.edit',['uuid'=>$grade->id])], Button::TYPE_DEFAULT,'edit') }}
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