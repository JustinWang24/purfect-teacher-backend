@php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
@endphp

@extends('layouts.app')
@section('content')
    <div class="row" id="banner-manager-app">
        <div class="col-sm-12 col-md-4">
            <div class="card">
                <div class="card-head">
                    <h4 class="text-center">资源位申请表 <i class="el-icon-loading" v-if="isLoading"></i></h4>
                </div>
                <div class="card-body p-3">
                    <el-form ref="bannerForm" :model="banner" label-width="80px">
                        <el-form-item label="位置">
                            <el-select v-model="banner.posit" placeholder="请选择位置">
                                <el-option v-for="(pos, idx) in positions" :label="pos" :value="idx" :key="idx"></el-option>
                            </el-select>
                        </el-form-item>

                        <el-form-item label="标题">
                            <el-input placeholder="必填: 标题" v-model="banner.title"></el-input>
                        </el-form-item>

                        <el-form-item label="排序号">
                            <el-input placeholder="必填: 排序号" v-model="banner.sort"></el-input>
                        </el-form-item>

                        <el-form-item label="类型">
                            <el-select v-model="banner.type" placeholder="请选择类型">
                                <el-option v-for="(ty, idx) in types" :label="ty" :value="idx" :key="idx"></el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item label="浏览权限">
                            <el-switch
                                    v-model="banner.public"
                                    active-text="不需要登录"
                                    inactive-text="只有登录后可见">
                            </el-switch>
                        </el-form-item>

                        <el-form-item label="浏览权限">
                            <el-switch
                                    v-model="banner.status"
                                    active-text="立即发布"
                                    inactive-text="暂不发布">
                            </el-switch>
                        </el-form-item>

                        <div>
                            <el-form-item label="图片">
                                <el-button type="primary" icon="el-icon-document" v-on:click="showFileManagerFlag=true">选择图片</el-button>
                            </el-form-item>
                            <div v-if="banner.image_url">
                                <p class="text-center mb-4">
                                    <img :src="banner.image_url" width="200">
                                </p>
                            </div>
                        </div>

                        <div v-if="isUrlOnly">
                            <el-form-item label="跳转地址">
                                <el-input placeholder="选填: 本资源位的跳转网址" type="textarea" v-model="banner.external"></el-input>
                            </el-form-item>
                        </div>

                        <div v-if="isPictureAndText">
                            <el-form-item label="文字说明">
                                <el-input rows="5" placeholder="选填: 本资源位的文字说明" type="textarea" v-model="banner.content"></el-input>
                            </el-form-item>
                        </div>
                        <el-form-item>
                            <el-button type="primary" @click="onSubmit">立即保存</el-button>
                            <el-button>取消</el-button>
                        </el-form-item>
                    </el-form>
                </div>
            </div>
            @include(
                'reusable_elements.section.file_manager_component',
                ['pickFileHandler'=>'pickFileHandler']
            )
        </div>
        <div class="col-sm-12 col-md-8">
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
                                    <th>权限</th>
                                    <th>位置</th>
                                    <th>类型</th>
                                    <th>标题</th>
                                    <th>图片</th>
                                    <th>状态</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $val)
                                    <tr>
                                        <td>{{ $val->id }}</td>
                                        <td>{{ $val->isPublicText() }}</td>
                                        <td>{{ $val->getPositionText() }} - {{ $val->sort }}</td>
                                        <td>{{ $val->getTypeText() }}</td>
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
    <div id="app-init-data-holder"
         data-school="{{ session('school.id') }}"
         data-positions="{{ json_encode(\App\Models\Banner\Banner::allPosit()) }}"
         data-types="{{ json_encode(\App\Models\Banner\Banner::allType()) }}"
    ></div>
@endsection
