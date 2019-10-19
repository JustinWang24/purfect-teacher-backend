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
                    <header>校区名: {{ session('school.name') }} - {{ $campus->name }}</header>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="row table-padding">
                            <div class="col-12">
                                <a href="{{ route('school_manager.school.view') }}" class="btn btn-default">
                                    返回 <i class="fa fa-arrow-circle-left"></i>
                                </a>&nbsp;
                                <a href="{{ route('school_manager.institute.add') }}" class="btn btn-primary pull-right">
                                    创建新学院 <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>学院名称</th>
                                    <th style="width: 300px;">简介</th>
                                    <th>院系数</th>
                                    <th>教职工数</th>
                                    <th>学生数</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($institutes as $index=>$institute)
                                    <tr>
                                        <td>{{ $index+1 }}</td>
                                        <td>
                                            {{ $institute->name }}
                                        </td>
                                        <td>{{ $institute->description }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('school_manager.institute.departments',['uuid'=>$institute->id]) }}">{{ count($institute->departments) }}</a>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('school_manager.institute.users',['type'=>User::TYPE_EMPLOYEE,'by'=>'institute','uuid'=>$institute->id]) }}">{{ $institute->employeesCount() }}</a>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('school_manager.institute.users',['type'=>User::TYPE_STUDENT,'by'=>'institute','uuid'=>$institute->id]) }}">{{ $institute->studentsCount() }}</a>
                                        </td>
                                        <td class="text-center">
                                            {{ Anchor::Print(['text'=>'编辑','href'=>route('school_manager.institute.edit',['uuid'=>$institute->id])], Button::TYPE_DEFAULT,'edit') }}
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