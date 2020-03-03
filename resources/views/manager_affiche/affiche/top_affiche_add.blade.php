<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>
@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-10 col-md-12 col-xl-12">
            <div class="card-box">
                <div class="card-head">
                    <header>添加</header>
                </div>
                <div class="card-body">
                    <form action="{{ route('manager_affiche.affiche.top_affiche_add') }}" method="post"  id="add-building-form">
                        @csrf
                        <div class="form-group">
                            <label for="school-name-input">学校</label>
                            <select id="cityid" class="form-control" name="school_id"  required>
                                <option value="0">社区未登录推荐</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="building-name-input">编号</label>
                            <input required type="text" class="form-control" id="building-name-input" value="" placeholder="请填写动态编号 例如：10" name="stick_mixid">
                        </div>
                        <?php
                        Button::Print(['id'=>'btn-create-building','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                        ?>&nbsp;
                        <?php
                        Anchor::Print(['text'=>trans('general.return'),'href'=>url()->previous(),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
	<script src="{{ route('manager_wifi.WifiApi.get_school_campus') }}" charset="UTF-8"></script>
    <script>
        window.onload=function() {
            showLocation();
        }
    </script>
@endsection
