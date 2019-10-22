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
                    <header>在学校 ({{ session('school.name') }}) 的 {{ $campus->name }} 创建新建筑</header>
                </div>
                <div class="card-body">
                    <form action="{{ route('school_manager.building.update') }}" method="post"  id="add-building-form">
                        @csrf
                        <input type="hidden" id="building-campus-id" name="building[campus_id]" value="{{ $campus->id }}">
                        <div class="form-group">
                            <label for="building-name-input">建筑名称</label>
                            <input required type="text" class="form-control" id="building-name-input" value="{{ $building->name }}" placeholder="建筑名称" name="building[name]">
                        </div>
                        <div class="form-group">
                            <label for="building-desc-input">简介</label>
                            <textarea class="form-control" name="building[description]" id="building-desc-input" cols="30" rows="10" placeholder="建筑简介">{{ $building->description }}</textarea>
                        </div>
                        <?php
                        Button::Print(['id'=>'btn-create-building','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
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