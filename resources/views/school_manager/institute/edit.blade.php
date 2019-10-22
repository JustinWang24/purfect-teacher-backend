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
                    <header>修改学校 ({{ session('school.name') }}) 的学院 - {{ $institute->name }}</header>
                </div>
                <div class="card-body">
                    <form action="{{ route('school_manager.institute.update') }}" method="post" id="edit-institute-form">
                        @csrf
                        <input type="hidden" id="institute-id-input" name="institute[id]" value="{{ $institute->id }}">
                        <input type="hidden" id="institute-campus-id-input" name="institute[campus_id]" value="{{ $institute->campus_id }}">
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