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
                    <header>添加 </header>
                </div>
                <div class="card-body " id="bar-parent">
                    <form action="{{ route('school_manager.consult.add') }}" method="post" id="edit-major-form">
                        @csrf
                        <div class="form-group">
                            <label for="major-name-input">问题</label>
                            <input required type="text" class="form-control" id="consult-question-input" value="" placeholder="问题" name="consult[question]">
                        </div>
                        <div class="form-group">
                            <label for="max-major">答案</label>
                            <textarea class="form-control" name="consult[answer]" id="major-desc-input" cols="30" rows="10" placeholder="答案"></textarea>
                        </div>

                        <?php
                        Button::Print(['id'=>'btn-create-campus','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                        ?>&nbsp;
                        <?php
                        Anchor::Print(['text'=>trans('general.return'),'href'=>route('school_manager.consult.list'),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
