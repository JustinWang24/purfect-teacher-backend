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
                    <form action="" method="post"  id="add-building-form">
                        @csrf
                        <input type="hidden" name="dataOne[contentid]" value="{{$dataOne['contentid']}}" id="building-id-input">
                        <div class="form-group">
                            <label for="school-name-input">类型</label>
                            <select class="form-control" name="infos[typeid]"  required>
                                <option value="">请选择</option>
                                @foreach($wifiContentsTypeArr as $key=>$val)
                                    <option value="{{$key}}">{{$val}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="building-name-input">内容</label>
                            <textarea required class="form-control" id="building-name-input" name="infos[content]" rows="10">
                                {{$dataOne['content']}}
                            </textarea>
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