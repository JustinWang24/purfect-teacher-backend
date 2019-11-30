<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>

@extends('layouts.app')
@section('content')
    <div class="row">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card-box">
                <div class="card-head">
                    <header>修改学校 ({{ session('school.name') }}) 的问卷</header>
                </div>
                <div class="card-body">
                    <form action="{{ route('school_manager.contents.questionnaire.update') }}" method="post"  id="edit-questionnaire-form">
                        @csrf
                        <input type="hidden" name="questionnaire[id]" value="{{ $questionnaire->id }}">
                        <div class="form-group">
                            <label for="questionnaire-title-input">问卷名称：</label>
                            <input required type="text" class="form-control" id="questionnaire-title-input" value="{{ $questionnaire->title }}" placeholder="问卷名称" name="questionnaire[title]">
                        </div>
                        <div class="form-group">
                            <label for="questionnaire-detail">简介：</label>
                            <textarea required class="form-control" name="questionnaire[detail]" id="questionnaire-desc-input" cols="30" rows="10" placeholder="简介">{{ $questionnaire->detail }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="questionnaire-option-input">第一个选项：</label>
                            <input required type="text" class="form-control" id="questionnaire-option-input" value="{{ $questionnaire->first_question_info }}" placeholder="问卷选项" name="questionnaire[first_question_info]">
                        </div>
                        <div class="form-group">
                            <label for="questionnaire-option-input">第二个选项：</label>
                            <input required type="text" class="form-control" id="questionnaire-option-input" value="{{ $questionnaire->second_question_info }}" placeholder="问卷选项" name="questionnaire[second_question_info]">
                        </div>
                        <div class="form-group">
                            <label for="questionnaire-option-input">第三个选项：</label>
                            <input required type="text" class="form-control" id="questionnaire-option-input" value="{{ $questionnaire->third_question_info }}" placeholder="问卷选项" name="questionnaire[third_question_info]">
                        </div>
                        <div class="from-group">
                            <label for="questionnaire-option-input">状态：</label>
                            <input type="radio" value="1" name="questionnaire[status]" @if($questionnaire['status'] == 1) checked @endif> 锁定&nbsp&nbsp&nbsp&nbsp
                            <input type="radio" value="2" name="questionnaire[status]" @if($questionnaire['status'] == 2) checked @endif> 激活
                        </div>
                        <?php
                        Button::Print(['id'=>'btn-create-questionnaire','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                        ?>&nbsp;
                        <?php
                        Anchor::Print(['text'=>trans('general.return'),'href'=>route('school_manager.contents.questionnaire'),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
