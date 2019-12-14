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
                    <form action="{{ route('teacher.dynamic.edit') }}" method="post"  id="add-forum-form">
                        @csrf
                        <input type="hidden" id="forum_id" name="forum[id]" value="{{$forum->id}}">

                        <div class="form-group">
                            <label for="forum-content">姓名</label>
                            <input type="text" disabled class="form-control" value="{{ $forum->user->name }}">
                        </div>

                        <div class="form-group">
                            <label for="forum-content">内容</label>
                            <textarea class="form-control" disabled   cols="30" rows="10">{{ $forum->content }}</textarea>
                        </div>

                        <div class="form-group">
                            <div class="form-group">
                                <label for="forum-status">状态</label>
                                <div>
                                    @if($forum->status == \App\Models\Forum\Forum::STATUS_UNCHECKED)

                                    <input type="radio" class="forum-control-radio" id="forum-status-radio-close" value="1"  name="forum[status]">拒绝&nbsp;&nbsp;&nbsp;
                                    <input type="radio" class="forum-control-radio" id="forum-status-radio-close" value="2"  name="forum[status]" checked>通过
                                    @else
                                    {{ $forum->statusText() }}
                                    @endif
                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <label for="forum-type">类别</label>
                            <select required type="select" class="form-control" id="forum-type-select" value="" placeholder="类型" name="forum[type_id]">
                                @foreach($forum_type as $key => $val)
                                <option value="{{ $val->id }}"
                                        @if($forum->type_id == $val->id)
                                            selected
                                            @endif
                                >{{ $val->title }}</option>
                                @endforeach
                            </select>
                        </div>



                        <div class="form-group">
                            <label for="forum-status">推荐</label>
                            <div>
                                <label class="switchToggle">
                                <input type="checkbox"
                                       @if($forum->is_up == \App\Models\Forum\Forum::OPEN )
                                       checked
                                       @endif
                                       name="forum[is_up]">
                                <span class="slider green round"></span>
                            </label>
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
