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
                    <header>修改</header>
                </div>
                <div class="card-body">
                    <form action="{{ route('teacher.conference.edit') }}" method="post"  id="edit-evaluate-form">
                        @csrf
                        <input type="hidden" id="conference-id" name="conference[id]" value="{{$conference->id}}">

                        @include('teacher.conference._form')
                        {{--判断是否为管理员--}}
                        @if($user->isSchoolAdminOrAbove())
                            <div class="form-group">
                                <label for="conference-remark-input">状态</label> &nbsp;&nbsp;
                                @if($conference->status == \App\Models\Teachers\Conference::STATUS_UNCHECK)
                                    <input type="radio" name="conference[status]" value="1"> 通过 &nbsp;&nbsp;
                                    <input type="radio" name="conference[status]" value="2"> 拒绝 &nbsp;&nbsp;
                                @else
                                    {{ $conference->checkStatus() }}
                                @endif
                            </div>
                        @else
                            <div class="form-group">
                                <label for="conference-remark-input">状态</label> &nbsp;&nbsp;
                                {{ $conference->checkStatus() }}
                            </div>
                        @endif


                        <?php
                        Button::Print(['id'=>'btn-edit-conference','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
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
