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
                    <header>修改</header>
                </div>
                <div class="card-body">
                    <form action="{{ route('teacher.communities.edit') }}" method="post"  id="add-forum-form">
                        @csrf
                        <input type="hidden" id="communities_id" name="communities[id]" value="{{$communities->id}}">
                        <input type="hidden" id="forum_type_id" name="communities[forum_type_id]" value="{{$communities->forum_type_id}}">
                        <input type="hidden" id="communities_school_id" name="communities[school_id]" value="{{$communities->school_id}}">
                        <input type="hidden" id="communities_name" name="communities[name]" value="{{$communities->name}}">

                        <div class="form-group">
                            <label for="forum-content">名称</label>
                            <input type="text" disabled class="form-control"  value="{{ $communities->name }}">
                        </div>

                        <div class="form-group">
                            <label for="forum-content">描述</label>
                            <textarea class="form-control" disabled   cols="30" rows="10">{{ $communities->detail }}</textarea>
                        </div>

                        <div class="form-group">
                            <div class="form-group">
                                <label for="forum-status">状态</label>
                                <div>

                                    <input type="radio" class="form-control-radio" id="form-status-radio-check" value="{{ \App\Models\Forum\Community::STATUS_CHECK }} "
                                           name="communities[status]" @if($communities->status == \App\Models\Forum\Community::STATUS_CHECK ) checked @endif>通过 &nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="radio" class="form-control-radio" id="form-status-radio-close" value="{{ \App\Models\Forum\Community::STATUS_CLOSE }} "
                                           name="communities[status]" @if($communities->status == \App\Models\Forum\Community::STATUS_CLOSE)  checked @endif>拒绝
                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <label for="forum-type">logo</label>

                        </div>


                         <?php
                        Button::Print(['id'=>'btn-edit-forum','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                        ?>&nbsp;
                        <?php
                        Anchor::Print(['text'=>trans('general.return'),'href'=>route('teacher.community.communities'),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
