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
                            <label for="building-desc-input">上班时间</label>
                            <input required type="text" class="form-control" id="building-name-input" value="{{ $group->online_time }}" placeholder="上班时间" name="group[online_time]">
                        </div>
                        <div class="form-group">
                            <label for="building-desc-input">下班时间</label>
                            <input required type="text" class="form-control" id="building-name-input" value="{{ $group->offline_time }}" placeholder="下班时间" name="group[offline_time]">
                        </div>
                        <div class="form-group">
                            <label for="building-desc-input">迟到计算时间</label>
                            <input required type="text" class="form-control" id="building-name-input" value="{{ $group->late_duration }}" placeholder="迟到计算时间" name="group[late_duration]">
                        </div>
                        <div class="form-group">
                            <label for="building-desc-input">严重迟到计算时间</label>
                            <input required type="text" class="form-control" id="building-name-input" value="{{ $group->serious_late_duration }}" placeholder="严重迟到计算时间" name="group[serious_late_duration]">
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
