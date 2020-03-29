<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>
@extends('layouts.app')
@section('content')
  <!--校园风光-->
    <div class="row" id="campus-intro-app" style="float:left;width:40%;height:450px;">
        <div class="col-12">

            <div class="card">
                <div class="card-head">
                  <header class="full-width">
                    <span class="pull-left" style="padding-top: 6px;">校园风光</span>
                  </header>
                </div>
                <div class="card-body">
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
              <div
                style="height: 40px;
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
                      width: 220px;"
              >点击上传封面图片</div>
              <div slot="tip" class="el-upload__tip" style="margin-bottom:20px;">图片格式为jpg/png，图片宽高比2:1</div>
              </el-upload>
                  <ele-upload-video
                    :file-size="50"
                    :height="250"
                    :width="420"
                    :error="handleUploadError"
                    :response-fn="handleResponse"
                    action="/api/welcome/uploadFiles"
                    v-model="video">
                  </ele-upload-video>
                </div>
            </div>
        </div>
    </div>

    <!--校园简介-->
    <div class="row" id="school-campus-intro-app" >
        <div class="col-12">
            <div class="card">
                <div class="card-head">
                    <header class="full-width">
                        <span class="pull-left" style="padding-top: 6px;">{{ $pageTitle }}</span>
                    </header>
                </div>
                <div class="card-body">
                    <form action="{{ route('school_manager.contents.save-campus-intro') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="config[school_id]" value="{{ $school->id }}">
                        <textarea id="content" name="config[campus_intro]">
						              {!! $campusIntro !!}</textarea>
                        <br>
                        <?php
                        Button::Print(['id'=>'btn-create-facility','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
