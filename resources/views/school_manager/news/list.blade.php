@extends('layouts.app')
@section('content')
    <div class="row" id="school-news-list-app">
        <div class="col-3">
            <div class="card">
                <div class="card-head">
                    <header class="full-width">
                        <span class="pull-left" style="padding-top: 6px;">{{ $pageTitle }}</span>
                        <button class="btn btn-primary btn-sm pull-right" v-on:click="addNew">
                            <i class="fa fa-plus"></i>添加{{ $typeText }}
                        </button>
                    </header>
                </div>
                <div class="card-body">
                    <el-table
                            :data="news"
                            style="width: 100%">
                        <el-table-column
                                label="标题">
                            <template slot-scope="scope">
                                <el-button type="text" @click="loadNews(scope.row.id)">
                                    @{{ scope.row.title }}
                                </el-button>
                                <p>状态: @{{ scope.row.publish ? '已发布':'等待发布' }}</p>
                            </template>
                        </el-table-column>
                        <el-table-column label="操作" width="80">
                            <template slot-scope="scope">
                                <el-button
                                        icon="el-icon-delete"
                                        size="mini"
                                        type="danger"
                                        @click="deleteNews(scope.row.id)"></el-button>
                            </template>
                        </el-table-column>
                    </el-table>

                    {{ $newsList->links() }}
                </div>
            </div>
        </div>

        <div class="col-5">
            <div class="card">
                <div class="card-head">
                    <header>
                        <span v-if="newsForm.title">
                            @{{ newsForm.title }}
                        </span>
                        <span v-if="newsFormFlag && !newsForm.title">新动态</span>
                        <i class="el-icon-loading" v-show="loading"></i>
                    </header>
                </div>
                <div class="card-body">
                    <div class="news-form-wrap" v-show="newsFormFlag">
                        <el-form :model="newsForm">
                            <el-form-item label="标题" :label-width="formLabelWidth">
                                <el-input v-model="newsForm.title" autocomplete="off"></el-input>
                            </el-form-item>
                            <el-form-item>
                                <el-button type="primary" @click="saveNews">开始编辑内容</el-button>
                                <el-button @click="cancelSaveNews">取消</el-button>
                            </el-form-item>
                        </el-form>
                    </div>
                    <div class="news-form-wrap" v-show="sectionsFormFlag">
                        <p v-show="!mediaContentWrapFlag && !textContentWrapFlag">
                            <el-button class="pull-left" icon="el-icon-document" type="primary" @click="addNewTextSection">添加文本内容</el-button>
                            <el-button class="pull-right" icon="el-icon-picture" type="primary" @click="addNewMediaSection">添加多媒体内容</el-button>
                        </p>
                        <div class="clearfix"></div>
                        <div class="text-content-wrap mt-4" v-show="mediaContentWrapFlag">
                            <el-form :model="mediaForm" label-width="80px">
                                <el-form-item label="选择图标">
                                    <el-button type="primary" icon="el-icon-picture" v-on:click="showFileManagerFlag=true">选择多媒体内容</el-button>
                                    <p v-if="selectedImgUrl" class="mt-4">
                                        <img :src="selectedImgUrl" width="200">
                                    </p>
                                </el-form-item>
                                <el-form-item>
                                    <el-button type="primary" @click="pushNewSection">保存</el-button>
                                    <el-button @click="cancelNewSection(2)">切换类型</el-button>
                                </el-form-item>
                            </el-form>
                        </div>
                        <div class="text-content-wrap mt-4" v-show="textContentWrapFlag">
                            <el-form :model="mediaForm">
                                <el-form-item label="内容">
                                    <el-input type="textarea" :rows="10" v-model="mediaForm.content"></el-input>
                                </el-form-item>
                                <el-form-item>
                                    <el-button type="primary" @click="pushNewSection">保存</el-button>
                                    <el-button @click="cancelNewSection(1)">切换类型</el-button>
                                </el-form-item>
                            </el-form>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="existed-section-wrapper">
                        <el-card class="box-card mt-4" v-for="(section, idx) in sections" :key="idx">
                            <div slot="header" class="clearfix">
                                <el-button v-on:click="moveUp(section)" v-if="idx > 0" style="float: left; padding: 3px 0" type="text">
                                    向上 <i class="el-icon-top"></i>
                                </el-button>
                                <el-button v-on:click="moveDown(section)" v-if="idx < sections.length - 1" style="float: left; padding: 3px 0" type="text">
                                    向下 <i class="el-icon-bottom"></i>
                                </el-button>
                                <el-button icon="el-icon-edit" v-on:click="editSection(section)" style="float: right; padding: 3px 0;" type="text">编辑</el-button>
                                <el-button icon="el-icon-delete" v-on:click="deleteSection(section)" style="float: right; padding: 3px 0;color: red;" type="text">删除</el-button>
                            </div>
                            <span v-if="section.media_id">
                                <img :src="section.content" style="max-width: 360px;">
                            </span>
                            <span v-else>@{{ section.content }}</span>
                        </el-card>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-4">
            <div class="card">
                <div class="card-head">
                    <header class="full-width">
                        <span class="pull-left" style="padding-top: 6px;">内容预览</span>
                        <el-button class="pull-right" size="mini" type="primary" @click="publish">发布</el-button>
                    </header>
                </div>
                <div class="card-body">
                    <div v-if="sections.length > 0" class="mobile-phone-previewer">
                        <div class="preview-wrap">
                            <h3 class="mb-4 text-primary">@{{ newsForm.title }}</h3>
                            <p v-for="(section, idx) in sections" :key="idx">
                                    <span v-if="section.media_id">
                                        <img :src="section.content" style="width: 360px;">
                                    </span>
                                <span v-else>@{{ section.content }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include(
                'reusable_elements.section.file_manager_component',
                ['pickFileHandler'=>'pickFileHandler']
            )
    </div>
    <div id="app-init-data-holder"
         data-school="{{ session('school.id') }}"
         data-type="{{ $type }}"
         data-news='{!! $newsList->toJson() !!}'
    ></div>
@endsection
