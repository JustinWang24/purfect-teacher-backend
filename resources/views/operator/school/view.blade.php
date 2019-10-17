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
                    <header>{{ session('school.name') }}</header>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="row table-padding">
                            <div class="col-md-6 col-sm-6 col-6">
                                <a href="{{ route('operator.campus.add') }}" class="btn btn-primary">
                                    创建新校区 <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table
                                    class="table table-striped table-bordered table-hover table-checkable order-column valign-middle"
                                    id="roles-table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>校区名称</th>
                                    <th style="width: 300px;">简介</th>
                                    <th>学院数</th>
                                    <th>教职工总数</th>
                                    <th>学生总数</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($school->campuses as $index=>$campus)
                                    <tr>
                                        <td>{{ $index+1 }}</td>
                                        <td>
                                            <a href="{{ route('operator.campus.view',['uuid'=>$school->uuid]) }}">{{ $campus->name }}</a>
                                        </td>
                                        <td>{{ $campus->description }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('operator.campus.institutes') }}">{{ count($campus->institutes) }}</a>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('operator.campus.users',['type'=>User::TYPE_EMPLOYEE]) }}">{{ $campus->employeesCount() }}</a>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('operator.campus.users',['type'=>User::TYPE_EMPLOYEE]) }}">{{ $campus->studentsCount() }}</a>
                                        </td>
                                        <td class="text-center">
                                            {{ Anchor::Print(['text'=>'编辑','href'=>route('operator.campus.edit',['uuid'=>$campus->uuid])], Button::TYPE_DEFAULT,'edit') }}
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