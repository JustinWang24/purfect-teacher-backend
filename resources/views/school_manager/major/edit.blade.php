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
                    <header>在学校 ({{ session('school.name') }}) - 编辑专业: {{ $major->name }}</header>
                </div>
                <div class="card-body " id="bar-parent">
                    <form action="{{ route('school_manager.major.update') }}" method="post" id="edit-major-form">
                        @csrf
                        <input type="hidden" id="major-id-input" name="major[id]" value="{{ $major->id }}">
                        <input type="hidden" id="major-department-id-input" name="major[department_id]" value="{{ $major->department_id }}">

                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="major-name-input">专业名称</label>
                                    <input required type="text" class="form-control" id="major-name-input" value="{{ $major->name }}" placeholder="专业名称" name="major[name]">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="major-name-input">自主招生</label>
                                    <select class="form-control" id="major-open-input" name="major[open]">
                                        <option value="0" {{ !$major->open ? 'selected':null }}>停止自主招生</option>
                                        <option value="1" {{ $major->open ? 'selected':null }}>开始自主招生</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="major-name-input">招生人数</label>
                                    <input required type="text" class="form-control" id="major-seats-input" value="{{ $major->seats }}" placeholder="招生人数" name="major[seats]">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="major-desc">简介</label>
                            <textarea class="form-control" name="major[description]" id="major-desc-input" cols="30" rows="10" placeholder="专业简介">{{ $major->description }}</textarea>
                        </div>
                        <?php
                        Button::Print(['id'=>'btn-save-major','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                        ?>&nbsp;
                        <?php
                        Anchor::Print(['text'=>trans('general.return'),'href'=>url()->previous(),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection