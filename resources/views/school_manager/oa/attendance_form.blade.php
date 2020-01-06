<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>

@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card">
                <div class="card-head">
                    <header>名称: {{ $group->name }}</header>
                </div>
                <div class="card-body " id="bar-parent">
                    <form action="{{ route('school_manager.oa.attendances-save') }}" method="post" id="edit-group-form">
                        @csrf
                        <input type="hidden" name="group[id]" value="{{ $group->id }}" id="building-id-input">
                        <div class="form-group">
                            <label for="building-name-input">组名称</label>
                            <input required type="text" class="form-control" id="building-name-input" value="{{ $group->name }}" placeholder="组名称" name="group[name]">
                        </div>
                        <div class="form-group">
                            <label for="building-desc-input">上午上班时间</label>
                            <input required type="text" class="form-control" id="building-name-input" value="{{ $group->morning_online_time }}" placeholder="上班时间" name="group[morning_online_time]">
                        </div>
                        <div class="form-group">
                            <label for="building-desc-input">上午下班时间</label>
                            <input required type="text" class="form-control" id="building-name-input" value="{{ $group->morning_offline_time }}" placeholder="下班时间" name="group[morning_offline_time]">
                        </div>
                        <div class="form-group">
                            <label for="building-desc-input">下午上班时间</label>
                            <input required type="text" class="form-control" id="building-name-input" value="{{ $group->afternoon_online_time }}" placeholder="上班时间" name="group[afternoon_online_time]">
                        </div>
                        <div class="form-group">
                            <label for="building-desc-input">下午下班时间</label>
                            <input required type="text" class="form-control" id="building-name-input" value="{{ $group->afternoon_offline_time }}" placeholder="下班时间" name="group[afternoon_offline_time]">
                        </div>
                        <div class="form-group">
                            <label for="building-desc-input">晚上上班时间</label>
                            <input required type="text" class="form-control" id="building-name-input" value="{{ $group->night_online_time }}" placeholder="上班时间" name="group[night_online_time]">
                        </div>
                        <div class="form-group">
                            <label for="building-desc-input">晚上下班时间</label>
                            <input required type="text" class="form-control" id="building-name-input" value="{{ $group->night_offline_time }}" placeholder="下班时间" name="group[night_offline_time]">
                        </div>
                        <div class="form-group">
                            <label for="building-desc-input">WIFI名称</label>
                            <input required type="text" class="form-control" id="building-name-input" value="{{ $group->wifi_name }}" placeholder="wifi名称" name="group[wifi_name]">
                        </div>
                        <?php
                        Button::Print(['id'=>'btn-create-questionnaire','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
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
