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
                    <header>修改</header>
                </div>
                <div class="card-body">
                    <form action="" method="post"  id="add-building-form">
                        @csrf
                        <input type="hidden" name="dataOne[contentid]" value="{{$dataOne['contentid']}}" id="building-id-input">
                        <div class="form-group">
                            <label for="building-name-input">内容</label>
							<textarea required class="form-control" name="infos[content]" id="questionnaire-desc-input" cols="30" rows="10" placeholder="">{{$dataOne['content']}}</textarea>
                        </div>
                       <?php
                       Button::Print(['id'=>'btn-create-building','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                       ?>&nbsp;
                       <?php
                       Anchor::Print(['text'=>trans('general.return'),'href'=>route('manager_wifi.wifiContent.list'),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                       ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection