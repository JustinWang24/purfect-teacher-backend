<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
use App\Models\Banner\Banner;
?>

@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card-box">
                <div class="card-head">
                    <header>添加</header>
                </div>
                <div class="card-body">
                    <form action="{{ route('school_manager.banner.save') }}" method="post">
                    @csrf
                        <div class="form-group">
                            <label for="facility-name-input">位置</label>
                            <select required type="select" class="form-control"  value="" placeholder="位置" name="banner[posit]">
                                <option value="">请选择</option>
                                @foreach($posit as $key => $val)
                                    <option value="{{$key}}">{{$val}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="facility-name-input">类型</label>
                            <select required type="select" class="form-control"  id="select" placeholder="类型" name="banner[type]">
                                <option value="-1">请选择</option>
                                @foreach($type as $key => $val)
                                     <option value="{{$key}}">{{$val}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="facility-name-input" id="name">标题</label>
                            <input required type="text" class="form-control"  placeholder="标题" name="banner[title]">
                        </div>

                         <div class="form-group" id="url">
                            <label for="facility-name-input" id="name">url</label>
                            <input required type="text" class="form-control"  placeholder="外部url" name="banner[external]">
                        </div>

                        <div class="form-group" id="image">
                            <label for="school-name-input">上传图片</label>
                            <input type="file" class="form-control" name="banner[image]" required>
                        </div>

                        <div class="form-group" id="image_text">
                            <label for="school-name-input">图文</label>
                            <textarea id="content" name="banner[content]"></textarea>
                        </div>

                        <?php
                        Button::Print(['id'=>'btn-create-facility','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                        ?>&nbsp;
                        <?php
                        Anchor::Print(['text'=>trans('general.return'),'href'=>url()->previous(),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS, 'arrow-circle-o-right')
                        ?>

                    {{--  todo:: 本页面需要下拉选中动态赋值给input框  required 属性 --}}
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

