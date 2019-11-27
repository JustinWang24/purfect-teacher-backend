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
                    <header>添加</header>
                </div>
                <div class="card-body">
                    <form action="{{ route('school_manager.notice.save') }}" method="post">
                    @csrf
                        <div class="form-group">
                            <label for="facility-name-input">类型</label>
                            <select required type="select" class="form-control"  id="select" placeholder="类型" name="notice[type]">
                                <option value="-1">请选择</option>
                                @foreach($notice_type as $key => $val)
                                    <option value="{{ $key }}"
                                            @if ($key == $data->type)
                                                selected
                                            @endif
                                    >{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>标题</label>
                            <input required type="text" class="form-control"  placeholder="标题" name="notice[title]" value="{{ $data->title }}">
                        </div>
                        <div class="form-group" id="image_text">
                            <label for="content">内容</label>
                            <textarea id="content" name="notice[content]">{{ $data->content }}</textarea>
                        </div>
                        {{-- todo :: 可见范围 和 上传封面 和 上传附件 需要vue来写 --}}
                        <div class="form-group">
                            <label>可见范围</label>
                            <input required type="text" class="form-control"  placeholder="可见范围" name="notice[organization_id]" value="{{ $data->organization_id }}">
                        </div>
                        <div class="form-group" id="image">
                            <label for="school-name-input">上传封面</label>
                            <input type="file" class="form-control" name="notice[image]">
                        </div>

                        <div class="form-group" id="file">
                            <label for="school-name-input">上传附件</label>
                            <input type="file" class="form-control" name="notice[media_id]">
                        </div>

                        <div class="form-group" id="note">
                            <label for="school-name-input">备注</label>
                            <input  type="text" class="form-control"  placeholder="标题" name="notice[note]" value="{{ $data->note }}">
                        </div>

                        <div class="form-group" id="inspect_id">
                            <label for="facility-name-input">检查类型</label>
                            <select  type="select" class="form-control"  placeholder="类型" name="notice[inspect_id]">
                                <option value="-1">请选择</option>
                                @foreach($inspect_type as $key => $val)
                                    <option value="{{ $val->id }}"
                                        @if ($val->id == $data->inspect_id)
                                            selected
                                        @endif>{{ $val->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" id="release_time">
                            <label for="school-name-input">发布时间</label>
                            <input  type="text" class="form-control"  placeholder="发布时间" name="notice[release_time]">
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

