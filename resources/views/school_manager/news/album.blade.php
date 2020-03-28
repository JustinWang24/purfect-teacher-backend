<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>
@extends('layouts.app')
@section('content')
    <div class="row" id="school-album-list-app">
        <div class="col-6">
            <div class="card">
                <div class="card-head">
                    <header class="full-width">
                        <span class="pull-left" style="padding-top: 6px;">{{ $pageTitle }}</span>
                    </header>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>标题</th>
                                <th>类型</th>
                                <th>预览</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($album as $item)
                                <tr>
                                    <td>
                                        {{ $item->title }}
                                    </td>
                                    <td>
                                        {{ $item->type === \App\Models\Contents\Album::TYPE_PHOTO ? '照片': '视频' }}
                                    </td>
                                    <td>
                                        @if($item->type === \App\Models\Contents\Album::TYPE_PHOTO)
                                            <img src="{{ $item->url }}" style="width: 200px;" alt="">
                                        @else
                                            <video src="{{ $item->url }}" style="width: 200px;" controls>
                                                您的浏览器不支持 video 标签。
                                            </video>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-danger btn-sm" href="{{ route('school_manager.contents.delete-album',['id'=>$item->id]) }}">删除</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card">
                <div class="card-head">
                    <header>
                        <span>
                            新照片或视频
                        </span>
                    </header>
                </div>
                <div class="card-body">
                    <form action="{{ route('school_manager.contents.create-album') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="album[school_id]" value="{{ $school->id }}">
                        <div class="form-group">
                            <label for="facility-name-input">类型</label>
                            <select required type="select" class="form-control" placeholder="类型" name="album[type]">
                                <option value="{{ \App\Models\Contents\Album::TYPE_PHOTO }}">相册图片</option>
                                <!--option value="{{ \App\Models\Contents\Album::TYPE_VIDEO }}">视频</option-->
                            </select>
                        </div>

                        <div class="form-group">
                            <label>标题</label>
                            <input required type="text" class="form-control"  placeholder="标题" name="album[title]">
                        </div>

                        <div class="form-group">
                            <label>选择文件</label>
                            <input required type="file" name="file" class="form-control" >
                        </div>

                        <?php
                        Button::Print(['id'=>'btn-create-facility','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
