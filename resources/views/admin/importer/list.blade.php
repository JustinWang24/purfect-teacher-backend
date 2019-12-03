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
                    <header>导入管理</header>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="table-padding col-12">

                                <a href="{{ route('admin.importer.add') }}" class="btn btn-primary " id="btn-create-versions-from">
                                    创建导入任务 <i class="fa fa-plus"></i>
                                </a>


                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>任务名</th>
                                    <th>状态</th>
                                    <th>文件路径</th>
                                    <th>文件统计信息</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($tasks) == 0)
                                    <tr>
                                        <td colspan="6">还没有内容 </td>

                                    </tr>
                                @endif
                                @foreach($tasks as $index=>$task)
                                    <tr>
                                        <td>{{ $index+1 }}</td>
                                        <td>{{ $task->title }}</td>
                                        <td>{{ $task->status }}</td>
                                        <td><pre>{{ $task->file_path }} </pre></td>
                                        <td class="text-center">{{ $task->file_info }}</td>
                                        <td class="text-center">
                                            {{ Anchor::Print(['text'=>'编辑','class'=>'btn-edit-building','href'=>route('admin.importer.edit',['id'=>$task->id])], Button::TYPE_DEFAULT,'edit') }}
                                            {{ Anchor::Print(['text'=>'导入','class'=>'btn-edit-building','href'=>route('admin.importer.handle',['id'=>$task->id])], Button::TYPE_DEFAULT,'handle') }}
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
