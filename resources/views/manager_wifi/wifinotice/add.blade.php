<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>
@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-10 col-md-12 col-xl-12">
            <div class="card-box">
                <div class="card-head">
                    <header>添加</header>
                </div>
                <div class="card-body">
                    <form action="{{ route('manager_wifi.wifiNotice.add') }}" method="post"  id="add-building-form">
                        @csrf
                        <div class="form-group">
                            <label for="building-name-input">标题</label>
                            <input required type="text" class="form-control" id="building-name-input" value="{{ old('infos.notice_title') }}" placeholder="" name="infos[notice_title]">
                        </div>
                        <div class="form-group">
                            <label for="building-name-input">内容</label>
                            <textarea required class="form-control" name="infos[notice_content]" id="questionnaire-desc-input" cols="30" rows="10" placeholder="">{{ old('infos.notice_content') }}</textarea>
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