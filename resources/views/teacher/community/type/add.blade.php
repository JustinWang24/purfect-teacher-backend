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
                    <header>添加</header>
                </div>
                <div class="card-body">
                    <form action="{{ route('teacher.community.dynamic.type.save') }}" method="post"  id="add-forum-form">
                        @csrf
                        <div class="col-4">
                            <div class="form-group">
                                <label for="forum-content">分类名称</label>
                                <input type="text"  class="form-control" value="" placeholder="分类名称" name="data[title]">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>属于</label>
                                <select class="form-control" name="data[type]">
                                @foreach($data as $key => $val)
                                        <option value="{{$key}}">{{$val}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                       <?php
                        Button::Print(['id'=>'btn-edit-forum','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                        ?>&nbsp;
                        <?php
                        Anchor::Print(['text'=>trans('general.return'),'href'=>route('teacher.community.dynamic'),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
