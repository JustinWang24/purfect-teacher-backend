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
                    <header>修改学校信息: {{ $school->name }}</header>
                </div>

                <div class="card-body " id="bar-parent">
                    <form action="{{ route('admin.schools.update') }}" method="post">
                        @csrf
                        <input type="hidden" name="school[uuid]" value="{{ $school->uuid }}">
                        <div class="form-group">
                            <label for="school-name-input">学校名称</label>
                            <input required type="text" class="form-control" id="school-name-input" value="{{ $school->name }}" placeholder="学校名称" name="school[name]">
                        </div>
                        <div class="form-group">
                            <label for="max-students">系统学生账户数量上限</label>
                            <input required type="text" class="form-control" value="{{ $school->max_students_number }}" id="max-students" placeholder="系统学生账户数量上限, 0表示无上限" name="school[max_students_number]">
                        </div>
                        <div class="form-group">
                            <label for="max-employees">系统教工账户数量上限</label>
                            <input required type="text" class="form-control" id="max-employees" value="{{ $school->max_employees_number }}" placeholder="系统教工账户数量上限, 0表示无上限" name="school[max_employees_number]">
                        </div>
                        <?php
Button::Print(['id'=>'btnSubmit','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
?>&nbsp;
                        <?php
Anchor::Print(['text'=>trans('general.return'),'href'=>route('home'),'class'=>'pull-right'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                        ?>
                        <p class="text-muted sub-title">上次更新: {{ $school->lastUpdatedBy->name ?? 'Admin' }}</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection