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
                    <header>在学校 ({{ session('school.name') }}) - {{ $department->name }} 创建新专业</header>
                </div>
                <div class="card-body " id="bar-parent">
                    <form action="{{ route('school_manager.major.update') }}" method="post" id="add-major-form">
                        @csrf
                        <input type="hidden" id="department-id-input" name="major[department_id]" value="{{ $department->id }}">
                        <input type="hidden" id="school-id-input" name="major[school_id]" value="{{ session('school.id') }}">
                        <div class="form-group">
                            <label for="major-name-input">专业名称</label>
                            <input required type="text" class="form-control" id="major-name-input" value="{{ $major->name }}" placeholder="专业名称" name="major[name]">
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