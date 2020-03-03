@extends('layouts.app')
@section('content')
<div class="row" id="teacher-oa-index-app">
    <div class="col-sm-12 col-md-12 col-xl-12">
        <div class="teacher_oa_card">
            <div class="teacher_oa_card_each" v-for="item in iconList" :key="item.name">
                <img :src="item.icon" alt="" class="teacher_oa_card_img">
                <p>@{{ item.name }}</p>
            </div>
        </div>
        <p class="teacher_oa_approval">我的审批</p>
        <div class="teacher_oa_approval_content">
            <div class="teacher_oa_approval_content_left">

            </div>
            <div class="teacher_oa_approval_content_right">
                <div class="teacher_oa_approval_content_right_tabs">
                    <ul>
                        <li v-for="(item,index) in nav" :key="index" @click="list_click(index)" :class="{'bgred':show==index}">@{{item.tit}}</li>
                    </ul>
                    <el-input placeholder="请输入审批类型/审批人">
                        <el-button slot="append">搜索</el-button>
                    </el-input>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="app-init-data-holder" data-school="{{ session('school.id') }}"></div>
@endsection