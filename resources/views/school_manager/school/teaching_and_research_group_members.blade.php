@extends('layouts.app')
@section('content')
    <div id="app-init-data-holder" data-school="{{ session('school.id') }}" data-group="{{ json_encode($group) }}" data-members="{{ json_encode($group->members) }}"></div>
    <div class="row" id="teaching-research-app">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-body">
                    <br>
                    <ul>
                        <li v-for="(m,idx) in members" :key="idx">
                            <p>@{{ m.user_name }}&nbsp; <el-button @click="deleteMember(idx)" type="text" class="text-danger">删除</el-button></p>
                        </li>
                    </ul>
                    <p class="text-info" v-if="members.length === 0">还没有添加成员</p>
                    <el-divider></el-divider>
                    <el-form ref="form" :model="group" label-width="120px">
                        <el-form-item label="教研组成员">
                            <search-bar school-id="{{ session('school.id') }}" scope="employee" full-tip="按名称查找" v-on:result-item-selected="onMemberSelected"></search-bar>
                        </el-form-item>
                        <el-form-item>
                            <el-button type="primary" @click="onMembersSubmit">保存</el-button>
                            <a href="{{ route('school_manager.organizations.teaching-and-research-group') }}" class="btn btn-default ml-4">返回</a>
                        </el-form-item>
                    </el-form>
                </div>
            </div>
        </div>
    </div>
@endsection
