@extends('layouts.app')
@section('content')
<div id="teacher-oa-logs-app">
    <div class="col-sm-12 col-md-12 col-xl-12">
        <div class="teacher-oa-logs-card">
            <div class="teacher-oa-logs-card_title">
                <p>日志</p>
                <p @click="drawer = true" type="primary">添加</p>
            </div>
            <el-drawer title="添加日志" :before-close="handleClose" :visible.sync="drawer" custom-class="demo-drawer" ref="drawer">
                <div class="demo-drawer__content">
                    <el-form :model="log">
                        <el-form-item label="标题">
                            <el-input v-model="log.title" placeholder="请输入标题" autocomplete="off"></el-input>
                        </el-form-item>
                        <el-form-item label="内容">
                            <el-input type="textarea" placeholder="请输入日志内容..." v-model="log.content"></el-input>
                        </el-form-item>
                    </el-form>
                    <div class="demo-drawer__footer">
                        <el-button type="primary" @click="addlog">发布</el-button>
                    </div>
                </div>
            </el-drawer>
            <ul class="teacher-oa-logs-card_type">
                <li v-for="item in nav" :key="item.type" @click="list_click(item.type)" :class="{'bgred':show==item.type}">@{{item.tit}}</li>
            </ul>
            <el-input placeholder="请输入标题关键字" class="teacher-oa-logs-card_search" v-model="keyword">
                <el-button slot="append" @click="getlogList(show)">搜索</el-button>
            </el-input>
            <!-- list -->
            <div class="teacher-oa-logs-card-list" v-for="item in logList" :key="item.id">
                <el-checkbox v-model="item.sele" v-show="nav[2].type"></el-checkbox>
                <div>
                    <div class="teacher-oa-logs-card-list-top">
                        <img :src="item.avatar" alt="">
                        <div class="teacher-oa-logs-card-list-top-right">
                            <p>@{{ item.title }}</p>
                            <p>@{{ item.created_at }}</p>
                        </div>
                    </div>
                    <div class="teacher-oa-logs-card-list-bottom">
                        <p>@{{ item.content }}</p>
                    </div>
                </div>
            </div>
            <!-- list -->
            <div v-show="nav[2].type == 1" class="teacher-oa-logs-card-button">
                <el-button @click="handleCheckAllChange">@{{btnText}}</el-button>
                <el-button type="primary">发送至</el-button>
            </div>
        </div>
    </div>
</div>

<div id="app-init-data-holder" data-school="{{ session('school.id') }}"></div>
@endsection