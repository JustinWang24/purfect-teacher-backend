<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>

@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card-box">
                <div class="card-head">
                    <header>已开通学校系统</header>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="table-scrollable">
                            <table
                                    class="table table-striped table-bordered table-hover table-checkable order-column valign-middle"
                                    id="roles-table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>学校名称</th>
                                    <th>最大学生数</th>
                                    <th>最大教工数</th>
                                    <th>最后修改</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($schools as $index=>$school)
                                    <tr>
                                        <td>{{ $index+1 }}</td>
                                        <td>
                                            <a href="{{ route('admin.schools.enter',['uuid'=>$school->uuid]) }}">{{ $school->name }}</a>
                                        </td>
                                        <td>{{ $school->max_students_number }}</td>
                                        <td>{{ $school->max_employees_number }}</td>
                                        <td>{{ $school->last_updated_by ? $school->lastUpdatedBy->mobile : '超级管理员' }} {{ $school->updated_at }}</td>
                                        <td class="text-center">
                                            {{ Anchor::Print(['text'=>'编辑','href'=>route('admin.schools.edit',['uuid'=>$school->uuid])], Button::TYPE_PRIMARY,'edit') }}
                                            {{ Anchor::Print(['text'=>'进入','href'=>route('admin.schools.enter',['uuid'=>$school->uuid])], Button::TYPE_SUCCESS,'arrow-circle-o-right') }}
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