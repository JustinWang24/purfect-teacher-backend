@extends('layouts.app')
@section('content')
    <div id="app-init-data-holder" data-school="{{ session('school.id') }}" data-group="{{ json_encode($group) }}"></div>
    <div class="row" id="teaching-research-app">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-body">
                    <br>
                    <el-form ref="form" :model="group" label-width="80px">
                        <el-form-item label="名称">
                            <el-input v-model="group.name" placeholder="必填: 名称"></el-input>
                        </el-form-item>
                        <el-form-item label="类别">
                            <el-select v-model="group.type" placeholder="请选择类别">
                                <el-option label="文化基础" value="文化基础"></el-option>
                                <el-option label="专业基础" value="专业基础"></el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item label="教研组长">
                            <search-bar :init-query="group.user_name" school-id="{{ session('school.id') }}" scope="employee" full-tip="按名称查找" v-on:result-item-selected="onUserSelected"></search-bar>
                        </el-form-item>
                        <el-form-item>
                            <el-button type="primary" @click="onSubmit">保存</el-button>
                            <a href="{{ route('school_manager.organizations.teaching-and-research-group') }}" class="btn btn-default ml-4">返回</a>
                        </el-form-item>
                    </el-form>
                </div>
            </div>
        </div>
    </div>
@endsection
