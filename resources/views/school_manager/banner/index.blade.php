@php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
@endphp

@extends('layouts.app')
@section('content')
    <div class="row" id="banner-manager-app">
        <div class="col-sm-12 col-md-12">
            <div class="card">
                <div class="card-head">
                    <header class="full-width">
                        <span class="pull-left pt-3">资源位列表</span>
                        <el-button class="pull-right" type="primary" @click="newBanner">添加</el-button>
                    </header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <tr>
                                    <th>序号</th>
                                    <th>应用</th>
                                    <th>位置</th>
                                    <th>类型</th>
                                    <th>标题</th>
                                    <th>图片</th>
                                    <th>状态</th>
                                    <th>添加时间</th>
                                    <th>操作</th>
                                </tr>
                                <tbody>
                                @foreach($data as $val)
                                    <tr>
                                        <td>{{ $val->id }}</td>
                                        <td>{{ $val->getAppStr($val->app) }}</td>
                                        <td>{{ $val->getPositStr($val->posit) }}</td>
                                        <td>{{ $val->getTypeStr($val->app) }}</td>
                                        <td>{{ $val->title }}</td>
                                        <td>
                                            @if($val->external)
                                                <a href="{{ $val->external }}" target="_blank">{{ $val->title }}</a>
                                            @else
                                                {{ $val->title }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($val->external)
                                                <a href="{{ $val->external }}" target="_blank">
                                                    <img src="{{ $val->image_url }}" width="200" alt="">
                                                </a>
                                            @else
                                                <img src="{{ $val->image_url }}" width="200" alt="">
                                            @endif

                                        </td>
                                        <td>
                                            @if($val['status'] == 1)
                                            <span class="label label-sm label-success"> 开启 </span>
                                                @else
                                            <span class="label label-sm label-danger"> 关闭 </span>
                                            @endif
                                        </td>
                                         <td class="text-center">
                                             <el-button size="mini" icon="el-icon-edit" @click="loadBanner({{ $val->id }})"></el-button>
                                             <el-button type="danger" size="mini" icon="el-icon-delete" @click="deleteBanner({{ $val->id }})"></el-button>
                                         </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $data->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
