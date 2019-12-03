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
                    <header>在学校 ({{ session('school.name') }}) 创建新问卷</header>
                </div>
                <div class="card-body">
                    <form action="{{ route('school_manager.contents.questionnaire.update') }}" method="post"  id="add-questionnaire-form">
                        @csrf
                        <div class="form-group">
                            <label for="questionnaire-title-input">问卷名称</label>
                            <input required type="text" class="form-control" id="questionnaire-title-input" value="{{ old('questionnaire.title') }}" placeholder="问卷名称" name="questionnaire[title]">
                        </div>
                        <div class="form-group">
                            <label for="questionnaire-detail">简介</label>
                            <textarea required class="form-control" name="questionnaire[detail]" id="questionnaire-desc-input" cols="30" rows="10" placeholder="简介">{{ old('questionnaire.detail') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="questionnaire-option-input">第一个选项</label>
                            <input required type="text" class="form-control" id="questionnaire-option-input" value="{{ old('questionnaire.first_question_info') }}" placeholder="问卷选项" name="questionnaire[first_question_info]">
                        </div>
                        <div class="form-group">
                            <label for="questionnaire-option-input">第二个选项</label>
                            <input required type="text" class="form-control" id="questionnaire-option-input" value="{{ old('questionnaire.second_question_info') }}" placeholder="问卷选项" name="questionnaire[second_question_info]">
                        </div>
                        <div class="form-group">
                            <label for="questionnaire-option-input">第三个选项</label>
                            <input required type="text" class="form-control" id="questionnaire-option-input" value="{{ old('questionnaire.third_question_info') }}" placeholder="问卷选项" name="questionnaire[third_question_info]">
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
