<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>
@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card">
                <div class="card-head">
                    <header>创建新版本号</header>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.versions.update') }}" method="post"  id="add-version-form" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="school-name-input">系统</label>
                            <select class="form-control" name="version[typeid]" id="version-typeid" required>
                                <option value="1">安卓</option>
                                <option value="2">Ios</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="school-name-input">终端</label>
                            <select class="form-control" name="version[user_apptype]"  required>
                                <option value="">请选择</option>
                                <option value="1">校园版</option>
                                <option value="2">教师版</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="school-name-input">更新</label>
                            <select class="form-control" name="version[isupdate]"  required>
                                <option value="">请选择</option>
                                <option value="1">强制更新</option>
                                <option value="2">不强制更新</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="version-code">弹框失效时间</label>
                            <input required type="datetime-local" class="form-control" id="version-code-input" value="{{ old('version.vserion_invalidtime') }}" placeholder="例如:2018-01-01 23" name="version[vserion_invalidtime]">
                        </div>
                        <div class="form-group">
                            <label for="version-code">版本号</label>
                            <input required type="text" class="form-control" id="version-code-input" value="{{ old('version.version_id') }}" placeholder="例如：1" name="version[version_id]">
                        </div>
                        <div class="form-group">
                            <label for="version-title-input">版本名称</label>
                            <input required type="text" class="form-control" id="version-title-input" value="{{ old('version.version_name') }}" placeholder="例如：1.1.0" name="version[version_name]">
                        </div>

                        <div class="form-group" id="azfiels">
                            <label for="version-file-input">选择文件</label>
                            <input id="file" type="file" class="form-control" name="source">
                        </div>

                        <div class="form-group" id="iosfiels" style="display: none;">
                            <label for="version-title-input">下载地址</label>
                            <input type="text" class="form-control" id="version-title-input" value="{{ old('version.version_downurl') }}" name="version[version_downurl]" placeholder="例如：https://itunes.apple.com/cn/app/易同学教师/id1390183907?mt=8">
                        </div>
                        <div class="form-group">
                            <label for="building-name-input">内容</label>
                            <textarea required class="form-control" name="version[version_content]" id="questionnaire-desc-input" cols="30" rows="10" placeholder="请输入更新的内容">{{ old('version.version_content') }}</textarea>
                        </div>
                        <?php
                        Button::Print(['id'=>'btn-create-questionnaire','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                        ?>&nbsp;
                        <?php
                        Anchor::Print(['text'=>trans('general.return'),'href'=>route('admin.versions.list'),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
