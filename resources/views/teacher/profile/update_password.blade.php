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
                    <header class="full-width">
                        修改密码
                    </header>
                </div>
                <div class="card-body">
                    <form action="{{ route('teacher.profile.update-password') }}" method="post">
                        @csrf
                        <input type="hidden" name="user[id]" value="{{ $user_id }}">
                        <div class="form-group">
                            <label>新密码</label>
                            <input required type="password" class="form-control" value="" placeholder="请输入新密码" name="user[password]">
                        </div>
                        <?php
                        Button::Print(['id'=>'btn-create-building','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                        ?>&nbsp;
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
