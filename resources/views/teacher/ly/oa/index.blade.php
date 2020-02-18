@extends('layouts.app')
@section('content')
<div class="row" id="teacher-oa-index-app">
    <div class="col-sm-12 col-md-12 col-xl-12">
        <div class="teacher_oa_card">
            <div class="teacher_oa_card_each">
                <span class="teacher_oa_card_img"></span>
                <p>通知公告</p>
            </div>
            <div class="teacher_oa_card_each">
                <span class="teacher_oa_card_img"></span>
                <p>日志</p>
            </div>
            <div class="teacher_oa_card_each">
                <span class="teacher_oa_card_img"></span>
                <p>内部信</p>
            </div>
            <div class="teacher_oa_card_each">
                <span class="teacher_oa_card_img"></span>
                <p>会议</p>
            </div>
            <div class="teacher_oa_card_each">
                <span class="teacher_oa_card_img"></span>
                <p>公文</p>
            </div>
            <div class="teacher_oa_card_each">
                <span class="teacher_oa_card_img"></span>
                <p>任务</p>
            </div>
        </div>
        <p class="teacher_oa_approval">我的审批</p>
        <div class="teacher_oa_approval_content">
            <div class="teacher_oa_approval_content_left">

            </div>
            <div class="teacher_oa_approval_content_right">
                
            </div>
        </div>
    </div>
</div>

<div id="app-init-data-holder" data-school="{{ session('school.id') }}"></div>
@endsection