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
                    <header>在学校 ({{ session('school.name') }}) 的 - {{ $campus->name }} - 添加新学院</header>
                </div>
                <div class="card-body">
                    <form action="{{ route('school_manager.institute.update') }}" method="post" id="add-institute-form">
                        @csrf
                        <input type="hidden" id="campus-id-input" name="institute[campus_id]" value="{{ $campus->id }}">
                        <input type="hidden" id="school-id-input" name="institute[school_id]" value="{{ session('school.id') }}">
                        <div class="form-group">
                            <label for="school-name-input">学院名称</label>
                            <input required type="text" class="form-control" id="institute-name-input" value="{{ $institute->name }}" placeholder="学院名称" name="institute[name]">
                        </div>
                        <div class="form-group">
                            <label for="institute-desc-input">简介</label>
                            <textarea class="form-control" name="institute[description]" id="institute-desc-input" cols="30" rows="10" placeholder="学院简介">{{ $institute->description }}</textarea>
                        </div>
                        <?php
                        Button::Print(['id'=>'btn-save-institute','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                        ?>&nbsp;
                        <?php
                        Anchor::Print(['text'=>trans('general.return'),'href'=>route('school_manager.school.view'),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection