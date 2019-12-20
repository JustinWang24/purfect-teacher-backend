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
                    <ul class="list-group list-group-unbordered">
                    @if($gradeManager)
                        <li class="list-group-item"><b>{{$student->gradeUser->grade->name}}的班长</b></li>
                    @else
                        <li class="list-group-item"><b>{{$student->gradeUser->grade->name}}的学生</b></li>
                    @endif
                    @foreach($student->community as $community)
                        <li class="list-group-item"><b>{{$community->name}}社团的团长</b></li>
                        @endforeach
                    <p>...</p>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
