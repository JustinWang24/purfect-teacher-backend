@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12" id="adviser-editor-app">
            <div class="card">
                <div class="card-head">
                    <header>
                        班级名称: {{ $grade->name }}
                    </header>
                </div>
                <div class="card-body">
                    <search-bar
                            init-query="{{ $gradeManager->monitor_name }}"
                            school-id="{{ session('school.id') }}"
                            scope="student"
                            full-tip="输入学生姓名进行搜索"
                            v-on:result-item-selected="adviserUpdated"
                    ></search-bar>
                    <el-button type="primary" v-on:click="onSubmit">保存</el-button>
                </div>
            </div>
        </div>
    </div>

    <div id="app-init-data-holder"
         data-school="{{ session('school.id') }}"
         data-adviser="{{ $gradeManager }}"
         data-url="{{ route('teacher.grade.set-monitor') }}"
         data-redirect="{{ route('school_manager.school.grades') }}"
         data-type="4"
    ></div>
@endsection