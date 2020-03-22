<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>
@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-10 col-md-12 col-xl-12">
            <div class="card-box">
                <div class="card-head">
                    <header>{{ $pageTitle }}</header>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.notifications.edit') }}" method="post"  id="add-building-form" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="uuid" value="{{ $dataOne->uuid }}">
                        <div class="form-group">
                          <label for="school-name-input">用户</label>
                          <select class="form-control" name="info[to]" required>
                            <option value="">请选择</option>
                            <option value="0"  @if( $dataOne->to == '0' ) selected @endif >所有用户</option>
                            <option value="-1" @if( $dataOne->to == '-1' ) selected @endif >教师用户</option>
                            <option value="-2" @if( $dataOne->to == '-2' ) selected @endif >学生用户</option>
                          </select>
                        </div>
                        <div class="form-group">
                            <label for="building-name-input">标题</label>
                            <input required type="text" class="form-control" id="building-name-input" value="{{ $dataOne->title }}" placeholder="请填写标题，字数范围在50以为" name="info[title]">
                        </div>
                        <div class="form-group">
                          <label for="building-name-input">内容</label>
                          <textarea id="content" name="info[content]" cols="20">{{ $dataOne->content }}</textarea>
                        </div>
                        <?php
                        Button::Print(['id'=>'btn-create-building','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                        ?>&nbsp;
                        <?php
                        Anchor::Print(['text'=>trans('general.return'),'href'=>route('admin.notifications.list'),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
