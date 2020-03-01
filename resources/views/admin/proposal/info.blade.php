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
                    <header>反馈详情</header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label>姓名：</label>
                                {{$data->user->name}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label>内容：</label>
                                {{$data->content}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-box">
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <label>图片：</label>
                            @foreach($data->images as $key => $val)
                            <div class="form-group">
                                <img src="{{$val->path}}">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <?php
            Anchor::Print(['text'=>trans('general.return'),'href'=>route('admin.proposal.list'),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
            ?>
        </div>
    </div>
@endsection
