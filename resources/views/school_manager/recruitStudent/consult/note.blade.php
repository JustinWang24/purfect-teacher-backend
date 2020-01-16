<?php
use App\Utils\UI\Button;
?>
@extends('layouts.app')
@section('content')
    <div class="row" id="enrol-note-manager-app">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card">
                <div class="card-head">
                    <header>报名须知</header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <form action="" method="post">
                                @csrf
                                <input type="hidden" name="note[school_id]" value="{{ \Illuminate\Support\Facades\Auth::user()->getSchoolId() }}">
                                <input type="hidden" name="note[plan_id]" value="0">
                                <div class="form-group">
                                    <label for="max-major">内容</label>
                                    <Redactor v-model="content" placeholder="请输入内容" :config="configOptions" name="note[content]" />
                                    @{{ content }}
                                </div>
                                <?php
                                Button::Print(['id'=>'btn-create-recruit-note','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                                ?>&nbsp;
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
