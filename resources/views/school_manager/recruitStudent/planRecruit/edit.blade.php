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
{{--                    <header>修改学校 ({{ session('school.name') }}) 的校区 - {{ $campus->name }}</header>--}}
                </div>
                <div class="card-body " id="bar-parent">
                    <form action="{{ route('school_manager.planRecruit.edit') }}" method="post" id="edit-major-form">
                        @csrf
                        <input type="hidden" name="major[id]" value="{{ $major['id'] }}">
                        <div class="form-group">
                            <label for="major-name-input">专业名称</label>
                            <input required type="text" class="form-control" id="major-name-input" value="{{ $major['name'] }}" placeholder="专业名称" name="major[name]">
                        </div>
                        <div class="form-group">
                            <label for="max-major">专业简介</label>
                            <textarea class="form-control" name="major[description]" id="major-desc-input" cols="30" rows="10" placeholder="专业简介">{{ $major['description'] }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="major-seats-input">计划招收</label>
                            <input required type="text" class="form-control" id="major-seats-input" value="{{ $major['seats'] }}" placeholder="专业名称" name="major[seats]">
                        </div>
                        <div class="form-group">
                            <label for="major-fee-input">学费</label>
                            <input required type="text" class="form-control" id="major-fee-input" value="{{ $major['fee'] }}" placeholder="专业名称" name="major[fee]">
                        </div>

                        <?php
                        Button::Print(['id'=>'btn-create-campus','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                        ?>&nbsp;
                        <?php
                        Anchor::Print(['text'=>trans('general.return'),'href'=>route('school_manager.planRecruit.list'),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
