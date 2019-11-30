<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>

@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-3">
            @include('student.elements.sidebar.avatar',['profile'=>$student->profile])
            @include('student.elements.sidebar.about_student',['profile'=>$student->profile])
        </div>
        <div class="col-sm-12 col-md-6 col-xl-6">
            <div class="card">
                <div class="card-head">
                    <header>用户资料管理 ({{ session('school.name') }}) - {{ $student->name }}</header>
                </div>
                <div class="card-body " id="bar-parent">
                @include('student.elements.form.profile',['student'=>$student])
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-3 col-xl-3">
            <div class="card">
                <div class="card-head">
                    <header>补充已注册学生的额外信息</header>
                </div>
                <div class="card-body " id="bar-parent">
                    <p>课程情况</p>
                    <p>提交的申请</p>
                    <p>...</p>
                </div>
            </div>
        </div>
    </div>
@endsection
