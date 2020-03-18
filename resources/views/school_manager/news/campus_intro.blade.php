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
                  <ele-upload-video :data="{token: token}"
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
