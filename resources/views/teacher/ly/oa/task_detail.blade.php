@extends('layouts.app')
@section('content')
    <div class="row task-common" id="teacher-oa-task-detail-app">
        <div class="col-sm-12 col-md-12 col-xl-12">
          <div class="page-header">任务详情</div>
          <task-detail />
        </div>
    <div id="app-init-data-holder"
         data-school="{{ session('school.id') }}"
    ></div>
@endsection
