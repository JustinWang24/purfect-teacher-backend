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
                    <header>修改</header>
                </div>
                <div class="card-body">
                    <form action="{{ route('school_manager.banner.save') }}" method="post">
                    @csrf
                        <input type="hidden" name="banner[id]" value="{{$data->id}}">

                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="facility-name-input">位置</label>
                                    <select required type="select" class="form-control"  value="" placeholder="位置" name="banner[posit]">
                                        <option value="">请选择</option>
                                        @foreach($posit as $key => $val)
                                            <option value="{{$key}}"
                                                    @if($data->posit == $key)
                                                    selected
                                                    @endif>
                                                {{$val}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="facility-name-input">类型</label>
                                    <select required type="select" class="form-control"  id="select" placeholder="类型" name="banner[type]">
                                        <option value="-1">请选择</option>
                                        @foreach($type as $key => $val)
                                            <option value="{{$key}}"
                                                    @if($data->type == $key)
                                                    selected
                                                    @endif>
                                                {{$val}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="facility-name-input">权限</label>
                                    <select required type="select" class="form-control"  id="public" placeholder="权限" name="banner[public]">
                                        <option value="1" {{ $data->public ? 'selected' : null }}>不需登录</option>
                                        <option value="0" {{ !$data->public ? 'selected' : null }}>需要登录</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="facility-name-input">标题</label>
                            <input required type="text" class="form-control"  placeholder="标题" name="banner[title]" value="{{ $data->title }}">
                        </div>

                        <div class="form-group">
                            <label>前端显示序号</label>
                            <input type="text" class="form-control"  placeholder="标题" name="banner[sort]" value="{{ $data->sort }}">
                        </div>

                        <div class="form-group">
                            <label for="school-name-input">上传图片</label>
                            <input type="file" class="form-control" name="banner[image_url]">
                            <img src="{{ $data->image_url }}" alt="" height="200">
                        </div>

                        <div class="form-group">
                            <label for="content">图文</label>
                            <textarea id="content" name="banner[content]">{!! $data->content !!}</textarea>
                        </div>

                        <?php
                        Button::Print(['id'=>'btn-create-facility','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                        ?>&nbsp;
                        <?php
                        Anchor::Print(['text'=>trans('general.return'),'href'=>url()->previous(),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS, 'arrow-circle-o-right')
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

