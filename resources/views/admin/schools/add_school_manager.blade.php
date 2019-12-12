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
                    <header>学校管理员账户: {{ $school->name }}</header>
                </div>
                <div class="card-body " id="bar-parent">
                    <form action="{{ route('admin.create.school-manager') }}" method="post">
                        @csrf
                        <input type="hidden" name="school_uuid" value="{{ $school->uuid }}">
                        <input type="hidden" name="user_uuid" value="{{ $user->uuid }}">
                        <div class="form-group">
                            <label>登陆账户名</label>
                            <input required type="text" class="form-control" value="{{ $user->mobile }}" placeholder="必填: 手机号" name="user[mobile]">
                        </div>
                        <div class="form-group">
                            <label>登陆密码</label>
                            <input type="password" class="form-control" value="" placeholder="登陆密码, 创建时必填" name="user[password]">
                        </div>
                        <div class="form-group">
                            <label>管理员姓名</label>
                            <input required type="text" class="form-control" value="{{ $user->name }}" placeholder="必填: 管理员真实姓名" name="user[name]">
                        </div>
                        <div class="form-group">
                            <label>管理员电子邮件</label>
                            <input required type="text" class="form-control" value="{{ $user->email }}" placeholder="必填: 管理员真实姓名" name="user[email]">
                        </div>
                        <?php
                        Button::Print(['id'=>'btnSubmit','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                        ?>&nbsp;
                        <?php
                        Anchor::Print(['text'=>trans('general.return'),'href'=>route('home'),'class'=>'pull-right'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection