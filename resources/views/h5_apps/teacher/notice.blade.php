@extends('layouts.h5_teacher_app')
@section('content')
    <div id="app-init-data-holder" data-school="{{ $school->id }}"></div>
    <div id="school-teacher-notices-list-app" class="school-intro-container">
        <div class="header">
            <h2 class="title">{{ $pageTitle }}</h2>
            <div v-for="(item, idx) in notices.data">
                <notice-item :notice="item" v-on:notice-clicked="showDetail"></notice-item>
            </div>
        </div>
        <div class="main">
            <el-drawer
                    :title="detail.title"
                    size="100%"
                    :visible.sync="showDetailFlag"
                    direction="rtl">
                <div style="padding-left: 16px;padding-right:16px;">
                    <p style="margin-top:0;">
                        <img :src="detail.image" style="width: 100%;">
                    </p>
                    <p>@{{ detail.content }}</p>
                    <p v-for="(p, idx) in detail.attachments" :key="idx">
                        <a :href="p.url">附件@{{ idx+1 }}: @{{ p.file_name }}</a>
                    </p>
                </div>
            </el-drawer>
        </div>
    </div>
@endsection
