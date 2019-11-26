@extends('layouts.app')
@section('content')
    <div class="row" id="school-news-list-app">
        <div class="col-4">
            <div class="card">
                <div class="card-head">
                    <header>
                        {{ session('school.name') }} - 校园动态
                        <button class="btn btn-primary btn-sm" v-on:click="addNew">添加动态</button>
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
        <button class="btn btn-sm btn-success"><i class="fa fa-edit"></i></button>
        <button class="btn btn-sm btn-danger"><i class="fa fa-trash-o"></i></button>
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
                        数据表
                    </header>
                </div>
                <div class="card-body">
                    <div class="news-form-wrap" v-show="newsFormFlag">
                        <el-form :model="newsForm">
                            <el-form-item label="活动名称" :label-width="formLabelWidth">
                                <el-input v-model="newsForm.title" autocomplete="off"></el-input>
                            </el-form-item>
                            <el-form-item>
                                <el-button type="primary" @click="saveNews">保存, 并进入下一步</el-button>
                                <el-button @click="cancelSaveNews">取消</el-button>
                            </el-form-item>
                        </el-form>
                    </div>
                    <div class="news-form-wrap" v-show="!sectionsFormFlag">
                        <el-button class="pull-left" icon="el-icon-document" type="primary" @click="addNewTextSection">添加文本内容</el-button>
                        <el-button class="pull-right" icon="el-icon-picture" type="primary" @click="addNewMediaSection">添加文本内容</el-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="app-init-data-holder"
         data-school="{{ session('school.id') }}"
         data-type="{{ \App\Models\Schools\News::TYPE_NEWS }}"
    ></div>
@endsection
