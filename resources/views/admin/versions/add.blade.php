<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>

@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card-box">
                <div class="card-head">
                    <header>创建新版本号</header>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.versions.update') }}" method="post"  id="add-version-form" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="version-title-input">版本名称</label>
                            <input required type="text" class="form-control" id="version-title-input" value="{{ old('version.name') }}" placeholder="版本名称" name="version[name]">
                        </div>
                        <div class="form-group">
                            <label for="version-code">版本号</label>
                            <input required type="text" class="form-control" id="version-code-input" value="{{ old('version.code') }}" placeholder="版本号" name="version[code]">
                        </div>
                        <div class="form-group">
                            <label for="version-file-input">选择文件</label>
                            <input id="file" type="file" class="form-control" name="source" required>
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
