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
                        <el-upload
                                action="#"
                                list-type="picture-card"
                                :auto-upload="false">
                            <i slot="default" class="el-icon-plus"></i>
                            <div slot="file" slot-scope="{file}">
                                <img
                                        class="el-upload-list__item-thumbnail"
                                        :src="file.url" alt=""
                                >
                                <span class="el-upload-list__item-actions">
                                <span
                                        class="el-upload-list__item-preview"
                                        @click="handlePictureCardPreview(file)"
                                >
                                  <i class="el-icon-zoom-in"></i>
                                </span>
                                <span
                                        v-if="!disabled"
                                        class="el-upload-list__item-delete"
                                        @click="handleDownload(file)"
                                >
                                  <i class="el-icon-download"></i>
                                </span>
                                <span
                                        v-if="!disabled"
                                        class="el-upload-list__item-delete"
                                        @click="handleRemove(file)"
                                >
                                  <i class="el-icon-delete"></i>
                                </span>
                              </span>
                            </div>
                        </el-upload>
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
