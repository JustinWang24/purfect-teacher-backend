@php
/**
* @var \App\Models\Course $course
*/
$courseTeacher = $course->getCourseTeacher($teacher->id);
@endphp
@extends('layouts.app')
@section('content')
    <div class="row" id="course-materials-manager-app">
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h2>{{ $course->name }} ({{ $course->duration }}课时)</h2>
                    <hr>
                    <p>我的说明</p>
                    <Redactor v-model="notes.teacher_notes" placeholder="请输入内容" :config="configOptions" />
                    @{{ notes.teacher_notes }}
                </div>
            </div>
        </div>
    </div>
    <div id="app-init-data-holder"
         data-school="{{ session('school.id') }}"
         data-course='{!! $course !!}'
         data-teacher='{!! $teacher !!}'
         data-notes='{!! $courseTeacher !!}'
    ></div>
@endsection
