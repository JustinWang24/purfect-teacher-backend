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
                    <header>在学校 ({{ session('school.name') }}) 创建新校区</header>
                </div>
                <div class="card-body">
                    <form action="{{ route('school_manager.campus.update') }}" method="post"  id="add-campus-form">
                        @csrf
                        <div class="form-group">
                            <label for="school-name-input">校区名称</label>
                            <input required type="text" class="form-control" id="campus-name-input" value="{{ $campus->name }}" placeholder="校区名称" name="campus[name]">
                        </div>
                        <div class="form-group">
                            <label for="max-students">简介</label>
                            <textarea required class="form-control" name="campus[description]" id="campus-desc-input" cols="30" rows="10" placeholder="校区简介">{{ $campus->description }}</textarea>
                        </div>
                        <?php
                        Button::Print(['id'=>'btn-create-campus','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
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
