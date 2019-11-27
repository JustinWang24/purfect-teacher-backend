@extends('layouts.app')
@section('content')
    <div class="row" id="school-news-list-app">
        <div class="col-4">
            <div class="card">
                <div class="card-head">
                    <header>
                        {{ session('school.name') }} - 校园动态 &nbsp;
                        <button class="btn btn-primary btn-sm" v-on:click="addNew"><i class="fa fa-plus"></i>添加动态</button>
                    </header>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover valign-middle">
                    <thead>
                        <tr>
                            <th>标题</th>
                            <th>状态</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($newsList as $news)
<tr>
    <td>{{ $news->title }}</td>
    <td>{{ $news->publish ? '发布':'等待' }}</td>
    <td>
        <button class="btn btn-sm btn-success" @click="loadNews({{ $news->id }})"><i class="fa fa-edit"></i></button>
        <button class="btn btn-sm btn-danger" @click="deleteNews({{ $news->id }})"><i class="fa fa-trash-o"></i></button>
    </td>
</tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $newsList->links() }}
                </div>
            </div>
        </div>

        <div class="col-8">
            <div class="card">
                <div class="card-head">
                    <header>
                        @{{ newsForm.title }} <i class="el-icon-loading" v-show="loading"></i>
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
                                    <el-button @click="cancelNewSection(2)">取消</el-button>
                                </el-form-item>
                            </el-form>
                        </div>
                        <div class="text-content-wrap mt-4" v-show="textContentWrapFlag">
                            <el-form :model="mediaForm">
                                <el-form-item label="内容">
                                    <el-input type="textarea" v-model="mediaForm.content"></el-input>
                                </el-form-item>
                                <el-form-item>
                                    <el-button type="primary" @click="pushNewSection">保存</el-button>
                                    <el-button @click="cancelNewSection(1)">取消</el-button>
                                </el-form-item>
                            </el-form>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div v-if="sections.length > 0" class="preview-wrap mt-4" style="width: 380px;margin: 0 auto;border: solid 1px #cccccc;padding: 10px;">
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

        @include(
                'reusable_elements.section.file_manager_component',
                ['pickFileHandler'=>'pickFileHandler']
            )
    </div>
    <div id="app-init-data-holder"
         data-school="{{ session('school.id') }}"
         data-type="{{ \App\Models\Schools\News::TYPE_NEWS }}"
    ></div>
@endsection
