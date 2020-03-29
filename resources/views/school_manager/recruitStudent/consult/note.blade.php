<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>
@extends('layouts.app')
@section('content')
    <div class="row" id="enrol-note-manager-app">
        <div class="col-sm-12 col-md-6 col-xl-6" >
            <div class="card">
                <div class="card-head">
                    <header>招生简章</header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <form action="" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group" id="azfiels">
                                    <label for="version-file-input">封面图</label>
                                    <input id="file" type="file" class="form-control" name="file">
                                </div>
                                <div class="form-group" id="azfiels">
                                    <img src="{{ $recruitment_intro_pics }}" class="img-responsive center-block">
                                </div>
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
@endsection
