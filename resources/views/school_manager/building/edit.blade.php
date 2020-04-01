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
                    <header>修改学校 ({{ session('school.name') }}) 的校区 - {{ $campus->name }} - 建筑: {{ $building->name }}</header>
                </div>
                <div class="card-body " id="bar-parent">
                    <form action="{{ route('school_manager.building.update') }}" method="post" id="edit-building-form">
                        @csrf
                        <input type="hidden" name="building[id]" value="{{ $building->id }}" id="building-id-input">
                        <input type="hidden" name="building[campus_id]" value="{{ $building->campus_id }}" id="building-campus-id-input">
                        <input type="hidden" name="building[type]" value="{{ $building->type }}" id="building-type  -input">
                        <div class="form-group">
                            <label for="building-desc-input">建筑编号</label>
                            <input required type="text" class="form-control" id="building-description-input" value="{{ $building->description }}" placeholder="建筑名称" name="building[description]">
                        </div>
                        <div class="form-group">
                            <label for="building-name-input">建筑名称</label>
                            <input required type="text" class="form-control" id="building-name-input" value="{{ $building->name }}" placeholder="建筑名称" name="building[name]">
                        </div>

                        <?php
                        Button::Print(['id'=>'btn-save-building','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
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
