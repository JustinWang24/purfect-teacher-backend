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
                <div class="card-body " id="bar-parent">
                    <form action="{{ route('operator.campus.update') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="school-name-input">校区名称</label>
                            <input required type="text" class="form-control" id="campus-name-input" value="{{ $campus->name }}" placeholder="学校名称" name="campus[name]">
                        </div>
                        <div class="form-group">
                            <label for="max-students">简介</label>
                            <textarea class="form-control" name="campus[description]" id="" cols="30" rows="10" placeholder="学校简介">{{ $campus->description }}</textarea>
                        </div>
                        <?php
                        Button::Print(['id'=>'btnSubmit','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                        ?>&nbsp;
                        <?php
                        Anchor::Print(['text'=>trans('general.return'),'href'=>route('operator.school.view'),'class'=>'pull-right'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection