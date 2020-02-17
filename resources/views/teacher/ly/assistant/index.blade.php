@extends('layouts.app')
@section('content')
<div class="row" id="teacher-assistant-index-app">
    <div class="col-sm-12 col-md-12 col-xl-12">
        <div class="teacher_assistant_card">
            <div class="teacher_assistant_card_each">
                <span class="teacher_assistant_card_img"></span>
                <p>课表</p>
            </div>
            <div class="teacher_assistant_card_each">
                <span class="teacher_assistant_card_img"></span>
                <p>教学资料</p>
            </div>
            <div class="teacher_assistant_card_each">
                <span class="teacher_assistant_card_img"></span>
                <p>签到</p>
            </div>
            <div class="teacher_assistant_card_each">
                <span class="teacher_assistant_card_img"></span>
                <p>评分</p>
            </div>
            <div class="teacher_assistant_card_each">
                <span class="teacher_assistant_card_img"></span>
                <p>选课</p>
            </div>
        </div>
        <p class="teacher_assistant_approval">班主任助手</p>
        <div class="teacher_assistant_card">
            <div class="teacher_assistant_card_each">
                <span class="teacher_assistant_card_img"></span>
                <p>班级管理</p>
            </div>
            <div class="teacher_assistant_card_each">
                <span class="teacher_assistant_card_img"></span>
                <p>学生信息</p>
            </div>
            <div class="teacher_assistant_card_each">
                <span class="teacher_assistant_card_img"></span>
                <p>班级签到</p>
            </div>
            <div class="teacher_assistant_card_each">
                <span class="teacher_assistant_card_img"></span>
                <p>班级评分</p>
            </div>
        </div>
        <p class="teacher_assistant_approval">学生审批</p>
    </div>
</div>

<div id="app-init-data-holder" data-school="{{ session('school.id') }}"></div>
@endsection