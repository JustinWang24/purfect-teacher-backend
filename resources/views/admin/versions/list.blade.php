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
                    <header>版本管理</header>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="table-padding col-12">

                                <a href="{{ route('admin.versions.add') }}" class="btn btn-primary " id="btn-create-versions-from">
                                    创建版本 <i class="fa fa-plus"></i>
                                </a>


                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>版本名</th>
                                    <th style="width: 800px;">版本号</th>
                                    <th>下载url</th>
                                    <th>本地磁盘路径</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($versions) == 0)
                                    <tr>
                                        <td colspan="6">还没有内容 </td>

                                    </tr>
                                @endif
                                @foreach($versions as $index=>$version)
                                    <tr>
                                        <td>{{ $index+1 }}</td>
                                        <td>{{ $version->name }}</td>
                                        <td>{{ $version->code }}</td>
                                        <td>{{ $version->download_url }} </td>
                                        <td class="text-center">{{ $version->local_path }}</td>
                                        <td class="text-center">
                                            {{ Anchor::Print(['text'=>'编辑','class'=>'btn-edit-building','href'=>route('admin.versions.edit',['id'=>$version->id])], Button::TYPE_DEFAULT,'edit') }}
                                            {{ Anchor::Print(['text'=>'删除','class'=>'btn-edit-building','href'=>route('admin.versions.delete',['id'=>$version->id])], Button::TYPE_DEFAULT,'delete') }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
