@extends('layouts.app')
@section('content')
<div id="teacher-assistant-grades-manager-app">
    <div class="blade_title">班级管理</div>
<el-row :gutter="20">
    <el-col :span="16">
        <div class="grid-content bg-purple-dark"></div>
        <div class="card">
            <div class="card-head">
                <header class="full-width">
                    班级风采
                </header>
            </div>
            <div class="card-body">
                <div class="card-content" v-for="item in gradeList">
                    <div class="content-head" v-html="item.name"></div>
                    <div class="content-body">
                        <div class="imageList"  v-for="img in item.image">
                            <img :src="img.path" alt="">
                            <i class="el-icon-error" @click="remove(img.image_id)"></i>
                        </div>
                        <div class="uploaderWrap" v-show="item.image.length < 3" @click="setId(item.grade_id)">
                            <el-upload
                                    class="avatar-uploader"
                                    action="##"
                                    list-type="picture-card"
                                    :auto-uplode='false'
                                    :show-file-list="false"
                                    with-credentials
                                    :before-upload="upload">
                                <i class="el-icon-plus avatar-uploader-icon"></i>
                            </el-upload>
                        </div>

                    </div>
                </div>
                <div v-show="gradeList.length = 0" class="no-data-img">
                    <img src="{{ asset('assets/img/teacher_blade/no-data.png') }}" alt="">
                    <p>当前列表暂时没有数据哦~</p>
                </div>
            </div>
        </div>
    </el-col>
</el-row>
</div>

<div id="app-init-data-holder"
     data-school="{{ session('school.id') }}"
></div>
@endsection
