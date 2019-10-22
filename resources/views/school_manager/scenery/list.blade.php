<?php
use App\Utils\UI\Button;
?>
@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-6 col-md-6 col-xl-6">
            <div class="card-box">
                <div class="card-head">
                    <header>校区名: {{ session('school.name') }} </header>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="row table-padding">
                            <div class="col-12">
                                <a href="{{ route('school_manager.school.view') }}" class="btn btn-default">
                                    返回 <i class="fa fa-arrow-circle-left"></i>
                                </a>&nbsp;
                                <a href="{{ route('scenery.add') }}" class="btn btn-primary pull-right">
                                    上传风采资源 <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>资源名称</th>
                                    <th class="text-center">资源路径</th>
                                    <th class="text-center">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                    @foreach($data as $info)
                                        <td>{{$info['name']}}</td>
                                        <td class="text-center">
                                            <div class="">
                                                <a href="#" class="thumbnail">
                                                    <img src="{{$info['path']}}" width="80px" height="80px">
                                                </a>
                                          </div>
                                        </td>
                                        <td class="text-center">
                                            @php
                                            Button::PrintGroup(
                                                [
                                                    'text'=>'可执行操作',
                                                    'subs'=>[
                                                        ['url'=>route('school_manager.student.edit',['uuid'=>'123']),'text'=>'编辑'],
                                                        ['url'=>route('school_manager.student.suspend',['uuid'=>'123']),'text'=>'删除'],
                                                        ['url'=>route('school_manager.student.suspend',['uuid'=>'123']),'text'=>'预览'],
                                                    ]
                                                ],
                                                Button::TYPE_PRIMARY
                                            )
                                            @endphp
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                {{ $data->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
