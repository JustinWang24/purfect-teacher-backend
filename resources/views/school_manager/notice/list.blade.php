@php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
@endphp

@extends('layouts.app')
@section('content')
<div class="row" id="notice-manager-app">
    <div class="col-sm-12 col-md-4 col-xl-4">
        <div class="card">
            <div class="card-head">
                <h4 class="text-center">通知申请表 <i class="el-icon-loading" v-if="isLoading"></i></h4>
            </div>
            <div class="card-body p-3">
                <el-form ref="noticeForm" :model="notice" label-width="80px">
                    <div>
                        <el-form-item label="可见范围" style="margin-bottom: 3px;">
                            <el-button type="primary" size="mini" icon="el-icon-document" v-on:click="showOrganizationsSelectorFlag=true">管理可见范围</el-button>
                        </el-form-item>
                        <el-form-item v-if="notice.selectedOrganizations.length > 0">
                            <el-tag
                                    v-for="item in notice.selectedOrganizations"
                                    :key="item.id"
                                    type="info"
                                    effect="plain"
                                    class="m-2"
                            >
                                @{{ item.name }}
                            </el-tag>
                        </el-form-item>
                        <el-divider></el-divider>
                    </div>
                    <el-form-item label="类型">
                        <el-select v-model="notice.type" placeholder="请选择类型">
                            <el-option v-for="(ty, idx) in types" :label="ty" :value="idx" :key="idx"></el-option>
                        </el-select>
                    </el-form-item>

                    <el-form-item label="标题">
                        <el-input placeholder="必填: 标题" v-model="notice.title"></el-input>
                    </el-form-item>

                    <el-form-item label="发布">
                        <el-switch
                                v-model="notice.status"
                                active-text="发布"
                                inactive-text="暂不发布">
                        </el-switch>
                    </el-form-item>

                    <el-form-item label="文字说明">
                        <el-input rows="5" placeholder="选填: 通知内容" type="textarea" v-model="notice.content"></el-input>
                    </el-form-item>
                    <el-form-item label="发布日期">
                        <el-date-picker
                                v-model="notice.release_time"
                                type="date"
                                format="yyyy-MM-dd"
                                value-format="yyyy-MM-dd"
                                placeholder="选择日期">
                        </el-date-picker>
                    </el-form-item>

                    <div>
                        <el-form-item label="封面图片">
                            <el-button type="primary" size="mini" icon="el-icon-document" v-on:click="showFileManagerFlag=true">选择封面图片</el-button>
                        </el-form-item>
                        <div v-if="notice.image">
                            <p class="text-center mb-4">
                                <img :src="notice.image" width="200">
                            </p>
                        </div>
                    </div>

                    <div>
                        <el-form-item label="附件">
                            <el-button size="mini" icon="el-icon-document" v-on:click="showAttachmentManagerFlag=true">选择附件</el-button>
                        </el-form-item>
                        <div v-if="notice.attachments && notice.attachments.length > 0">
                            <p class="text-center mb-4" v-for="(atta, idx) in notice.attachments" :key="idx">
                                <span class="">
                                    <a :href="atta.url" target="_blank">附件@{{ idx + 1 }}: @{{ atta.file_name }}</a>
                                </span>
                                <el-button type="text" class="pt-1 text-danger pull-right" @click="deleteNoticeMedia(atta.id)">删除</el-button>
                            </p>
                        </div>
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
        @include(
            'reusable_elements.section.file_manager_component',
            ['pickFileHandler'=>'pickAttachmentHandler','syncFlag'=>'showAttachmentManagerFlag']
        )
        @include(
            'reusable_elements.section.organizations_selector',
            ['organizationsSelectedHandler'=>'onOrganizationsSelectedHandler','schoolId'=>$schoolId, 'userRoles'=>$userRoles]
        )
    </div>
    <div class="col-sm-12 col-md-8 col-xl-8">
        <div class="card">
            <div class="card-head">
                <header class="full-width">
                    <span class="pull-left pt-3">列表</span>
                    <el-button class="pull-right" type="primary" @click="newNotice">添加</el-button>
                </header>
            </div>
            <div class="card-body">
                <div class="row">

                </div>
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                            <tr>
                                <th>可见范围</th>
                                <th>标题</th>
                                <th>类型</th>
                                <th>封面图片</th>
                                <th>发布时间</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $val)
                                <tr>
                                    <td>
                                        @foreach($val->selectedOrganizations as $so)
{{ $so->organization->name??null }}
                                        @endforeach
                                    </td>
                                    <td>{{ $val->title }}</td>
                                    <td>{{ $val->getTypeText() }}</td>
                                    <td>
                                        <img src="{{ $val->image }}" width="200">
                                    </td>
                                    <td>{{ _printDate($val->release_time) }}</td>
                                    <td>
                                        @if($val['status'] == 1)
                                        <span class="label label-sm label-success"> 已发布 </span>
                                            @else
                                        <span class="label label-sm label-danger"> 未发布 </span>
                                        @endif
                                    </td>
                                     <td class="text-center">
                                         <el-button size="mini" icon="el-icon-edit" @click="loadNotice({{ $val->id }})"></el-button>
                                         <el-button type="danger" size="mini" icon="el-icon-delete" @click="deleteNotice({{ $val->id }})"></el-button>
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
     data-types="{{ json_encode(\App\Models\Notices\Notice::allType()) }}"
></div>
@endsection
