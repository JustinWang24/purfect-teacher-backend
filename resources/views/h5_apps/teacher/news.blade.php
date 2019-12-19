@extends('layouts.h5_teacher_app')
@section('content')
    <div id="app-init-data-holder" data-school="{{ $school->id }}" data-type="{{ $typeId }}"></div>
    <div id="school-teacher-news-list-app" class="school-intro-container">
        <div class="header">
            <h2 class="title">{{ $pageTitle }}</h2>
            <div v-for="(item, idx) in news.data">
                <news-item :news="item" v-on:news-clicked="showDetail"></news-item>
            </div>
        </div>
        <div class="main">
            <el-drawer
                    title="{{ $pageTitle }}"
                    size="100%"
                    :visible.sync="showDetailFlag"
                    direction="rtl">
                <div style="padding-left: 16px;padding-right;">
                    <h1 style="margin-top:0;">@{{ detail.title }}</h1>
                    <p v-for="(p, idx) in detail.sections" :key="idx">
                    <span v-if="p.media_id === ''">
                        @{{ p.content }}
                    </span>
                        <img v-if="p.media_id" :src="p.content" style="width: 100%;">
                    </p>
                </div>
            </el-drawer>
        </div>
    </div>
@endsection
