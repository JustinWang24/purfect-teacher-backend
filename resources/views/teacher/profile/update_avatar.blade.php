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
                        {{ $user->name }}照片
                    </header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <form action="{{ route('school_manager.teachers.edit-avatar') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="user[id]" value="{{ $user->id }}">
                                <div class="form-group">
                                    <label>照片文件</label>
                                    <input required type="file" class="form-control" name="file">
                                </div>
                                <?php
                                Button::Print(['id'=>'btn-create-building','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                                ?>&nbsp;
                            </form>
                        </div>
                        <div class="col-6">
                            <p>当前照片: </p>
                            <img src="{{ $user->profile->avatar }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
