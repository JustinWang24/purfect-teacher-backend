<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>
@extends('layouts.app')
@section('content')
    <div class="row" id="enrol-note-manager-app" id="enrol-note-manager-child">
        <div class="col-sm-12 col-md-6 col-xl-6" >
            <div class="card">
                <div class="card-head">
                    <header>招生简章( 封面图 / 内容 )</header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <el-upload
                            ref="upload"
                            class="upload-demo"
                            action="{{ route('uploadFiles') }}"
                            :on-remove="handleRemove"
                            :on-success="saveFile"
                            :before-upload="handlePreview"
                            :file-list="fileList"
                            :limit="1"
                            list-type="picture">
                        <div style="
                          background-color: #fff;
                          background-image: none;
                          border-radius: 4px;
                          border: 1px solid #dcdfe6;
                          box-sizing: border-box;
                          color: #606266;
                          display: inline-block;
                          font-size: inherit;
                          line-height: 40px;
                          outline: 0;
                          padding: 0 15px;
                          transition: border-color .2s cubic-bezier(.645,.045,.355,1);
                          width: 300px;margin: 30px;">点击上传封面图片( 图片宽高比2:1 )
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <form action="" method="post">
                                @csrf
                                <input type="hidden" name="config[school_id]" value="{{ session('school.id') }}">
                                <input type="hidden" name="config[recruitment_intro_pics]" v-model="image_url">
                                <div class="form-group">
                                    <Redactor v-model="recruitment_intro" placeholder="请输入招生简章" :config="configOptions" name="config[recruitment_intro]" />
                                    @{{ recruitment_intro }}
                                </div>
                                <?php
                                Button::Print(['id'=>'btn-create-recruit-note','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                                ?>&nbsp;
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-xl-6">
            <div class="card">
                <div class="card-head">
                    <header>报名须知</header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <form action="" method="post">
                                @csrf
                                <input type="hidden" name="note[school_id]" value="{{ session('school.id') }}">
                                <input type="hidden" name="note[plan_id]" value="0">
                                <div class="form-group">
                                    <Redactor v-model="content" placeholder="请输入报名须知" :config="configOptions" name="note[content]" />
                                    @{{ content }}
                                </div>
                                <?php
                                Button::Print(['id'=>'btn-create-recruit-note','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                                ?>&nbsp;
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="app-init-data-holder" data-imageurl="{{ $recruitment_intro_pics }}"></div>
@endsection
