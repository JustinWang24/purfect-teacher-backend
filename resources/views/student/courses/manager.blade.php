@extends('layouts.app')
@section('content')
    <div id="course-student-manager-app">
        <div class="row">
            <el-menu default-active="1" class="el-menu-demo" mode="horizontal" @select="handleMenuSelect" style="margin: 0 auto;">
                <el-menu-item index="1">选择课节(第@{{ highlight }}节)</el-menu-item>
                <el-menu-item index="2"><a href="https://www.baidu.com" target="_blank">查看课表</a></el-menu-item>
            </el-menu>
        </div>

        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h2>{{ $course->name }} (总计{{ $course->duration }}课时)</h2>
                        <hr>
                        @foreach($lectures as $lecture)
<p>{{ $lecture->title }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <div class="card">
                    <div class="card-body">

                    </div>
                </div>
            </div>
        </div>

        <el-dialog title="{{ $course->name }} ({{ $course->duration }}课时)" :visible.sync="courseIndexerVisible">
            <course-indexer :count="{{ $course->duration }}" :highlight="highlight" v-on:index-clicked="switchCourseIndex"></course-indexer>
        </el-dialog>
    </div>
    <div id="app-init-data-holder"
         data-school="{{ session('school.id') }}"
         data-course='{!! $course !!}'
         data-student='{!! $student !!}'
    ></div>
@endsection
