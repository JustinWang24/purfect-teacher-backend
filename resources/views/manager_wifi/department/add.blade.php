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
                    <header>在学校 ({{ session('school.name') }}) 的 - {{ $institute->name }} - 添加新系</header>
                </div>
                <div class="card-body">
                    <form action="{{ route('school_manager.department.update') }}" method="post" id="add-department-form">
                        @csrf
                        <input type="hidden" id="institute-id-input" name="department[institute_id]" value="{{ $institute->id }}">
                        <input type="hidden" id="school-id-input" name="department[school_id]" value="{{ session('school.id') }}">
                        <div class="form-group">
                            <label for="department-name-input">系名称</label>
                            <input required type="text" class="form-control" id="department-name-input" value="{{ $department->name }}" placeholder="系名称" name="department[name]">
                        </div>
                        <div class="form-group">
                            <label for="department-desc-input">简介</label>
                            <textarea class="form-control" name="department[description]" id="department-desc-input" cols="30" rows="10" placeholder="系简介">{{ $department->description }}</textarea>
                        </div>
                        <?php
                        Button::Print(['id'=>'btn-save-department','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
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