@extends('layouts.app')
@section('content')
<div id="teacher-oa-notices-app">
    <!-- <div class="col-sm-12 col-md-12 col-xl-12"> -->
    <div class="notices-card teacher-oa-notices-app-one">
        <div class="notices-card-header">
            <p>通知公告</p>
            <p class="release">发布</p>
        </div>
        <div class="teacher-oa-notices-app-one-body" v-for="item in oneList" :key="item.id" @click="oneDetail(item.id)">
            <div class="teacher-oa-notices-app-one-body-title">
                <p>@{{ item.title }}</p>
                <p>@{{ item.is_read == 0 ? '未读' : '已读' }}</p>
            </div>
            <p class="teacher-oa-notices-app-one-body-content">@{{ item.content }}</p>
            <p class="teacher-oa-notices-app-one-body-time">@{{ item.created_at }}</p>
        </div>
    </div>
    <div class="notices-card teacher-oa-notices-app-two">
        <div class="notices-card-header">
            <p>公告</p>
        </div>
        <div class="teacher-oa-notices-app-two-body" v-for="item in twoList" :key="item.id" @click="oneDetail(item.id)">
            <img :src="item.image" alt="">
            <div class="teacher-oa-notices-app-two-body-title">
                <p>@{{ item.is_read == 0 ? '未读' : '已读' }}</p>
                <p>@{{ item.title }}</p>
                <p>@{{ item.created_at }}</p>
            </div>
        </div>
    </div>
    <div class="notices-card teacher-oa-notices-app-three">
        <div class="notices-card-header">
            <p>检查</p>
        </div>
        <div class="teacher-oa-notices-app-three-body" v-for="item in threeList" :key="item.id" @click="oneDetail(item.id)">
            <div class="teacher-oa-notices-app-three-body-title">
                <p>@{{ item.title }}</p>
                <p>@{{ item.is_read == 0 ? '未读' : '已读' }}</p>
            </div>
            <p class="teacher-oa-notices-app-three-body-content">@{{ item.content }}</p>
            <div class="teacher-oa-notices-app-three-body-time">
                <p v-show="item.inspect != ''">@{{ item.inspect }}</p>
                <p>@{{ item.created_at}}</p>
            </div>
        </div>
    </div>
    <el-drawer :title="titleName" :before-close="handleClose" :visible.sync="drawer" custom-class="demo-drawer">
        <div class="demo-drawer__content">
            <p>@{{detail.title}}</p>
            <p>@{{detail.created_at}}</p>
            <p>@{{detail.content}}</p>
            <img :src="detail.image" alt="" v-if="detail.type != 1">
            <div class="demo-drawer__content_enclosure" v-if="attachments.length">
                <p class="word">附件</p>
                <p class="enclosure">@{{ attachments[0].file_name}}</p>
            </div>
        </div>
    </el-drawer>
    <!-- </div> -->
</div>

<div id="app-init-data-holder" data-school="{{ session('school.id') }}"></div>
@endsection