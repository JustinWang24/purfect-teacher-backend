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
                    <header>在学校 ({{ session('school.name') }}) 添加资源</header>
                </div>
                <div class="card-body " id="bar-parent">
                    <form action="{{ route('school_manager.campus.update') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="school-name-input">校区名称</label>
                            <input required type="text" class="form-control" id="campus-name-input" value="" placeholder="资源名称" name="">
                        </div>
                        <div class="col-lg-12 p-t-20">
                            <label class="control-label col-md-3">Course Photo </label>
                            <div class="col-md-12">
                                <div id="id_dropzone" class="dropzone dz-clickable"><div class="dz-default dz-message"><span>Drop files here to upload</span></div></div>
                            </div>
						</div>
                        <?php
                        Button::Print(['id'=>'btnSubmit','text'=>trans('')], Button::TYPE_PRIMARY);
                        ?>&nbsp;
                        <?php
                        Anchor::Print(['text'=>trans('general.return'),'href'=>route('school_manager.school.view'),'class'=>'pull-right'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
