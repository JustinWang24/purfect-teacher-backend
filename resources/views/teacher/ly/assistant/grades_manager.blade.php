@extends('layouts.app')
@section('content')
<div id="teacher-assistant-grades-manager-app">
    <div class="blade_title">班级管理</div>
    <el-row :gutter="20">
        <el-col :span="16">
            <div class="grid-content bg-purple-dark"></div>
            <div class="card">
                <div class="card-head">
                    <header class="full-width">
                        班级风采
                    </header>
                </div>
                <div class="card-body">
                    <div v-show="0" class="no-data-img">
                        <img src="{{ asset('assets/img/teacher_blade/no-data.png') }}" alt="">
                        <p>当前列表暂时没有数据哦~</p>
                    </div>
                </div>
            </div>
        </el-col>
    </el-row>
</div>

<div id="app-init-data-holder"
     data-school="{{ session('school.id') }}"
></div>
@endsection
