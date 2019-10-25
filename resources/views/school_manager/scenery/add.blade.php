<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
use App\Models\Schools\SchoolResource;
?>

@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card-box">
                <div class="card-head">
                    <header>在学校 ({{ session('school.name') }}) 添加资源</header>
                </div>
                <div class="card-body " id="bar-parent">
                    <form action="{{ route('school_manager.scenery.save') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="school-name-input">资源类型</label>
                            <select class="form-control" name="scenery[type]"  required>
                                <option value="">Select...</option>
                                <option value="{{SchoolResource::TYPE_IMAGE}}">图片</option>
                                <option value="{{SchoolResource::TYPE_VIDEO}}">视频</option>
                            </select>
                        </div>

                        <div id="image" style="">
                            <div class="form-group">
                                <lable for="">资源名称</lable>
                                <input type="text"  class="form-control" name="scenery[name]" required>
                            </div>
                            <div class="form-group">
                                <lable for="">宽度</lable>
                                 <div class="input-group">
                                    <input type="text"  class="form-control" name="scenery[width]"  required >
                                    <span class="input-group-addon">PX</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <lable for="">高度</lable>
                                 <div class="input-group">
                                    <input type="text"  class="form-control" name="scenery[height]"  required >
                                    <span class="input-group-addon">PX</span>
                                </div>
                            </div>
{{--                            <div class="form-group">--}}
{{--                                <label for="school-name-input">上传图片</label>--}}
{{--                                <input type="file" class="form-control" name="image" required>--}}
{{--                            </div>--}}
                        </div>

                        <div id="video">
                            <div class="form-group">
                                <label for="school-name-input">上传视频封面</label>
                                <input type="file" class="form-control" name="video_cover" required>
                            </div>
                            <div class="form-group">
                                <label for="school-name-input">上传视频</label>
                                <input type="file" class="form-control" name="video" required>
                            </div>
                        </div>

                        <?php
                        Button::Print(['id'=>'btnSubmit','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                        ?>&nbsp;
                        <?php
                        Anchor::Print(['text'=>trans('general.return'),'href'=>route('school_manager.school.view'),'class'=>'pull-right'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

