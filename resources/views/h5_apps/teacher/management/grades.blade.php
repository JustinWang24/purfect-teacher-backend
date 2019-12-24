@extends('layouts.h5_teacher_app')
@section('content')
    <div id="app-init-data-holder" data-school="{{ $teacher->getSchoolId() }}"
         data-grades="{{ json_encode($grades) }}" data-token="{{ $api_token }}"></div>
    <div id="school-teacher-management-grades-app" class="school-intro-container">
        <div class="main p-15">
            <h4>
                <el-button type="text" icon="el-icon-arrow-left" class="text-dark" @click="back"></el-button>&nbsp;
                我的班级: {{ $year }} 第{{ $term }}学期
            </h4>
            <el-table
                    :data="grades"
                    empty-text="没有班级记录"
                    stripe
                    style="width: 100%">
                <el-table-column
                        label="班级名">
                    <template slot-scope="scope">
                        <el-button @click="showDetail(scope.row)" type="text">@{{ scope.row.name }}</el-button>
                    </template>
                </el-table-column>
                <el-table-column label="年级">
                    <template slot-scope="scope">
                        @{{ scope.row.year }}
                    </template>
                </el-table-column>
                <el-table-column label="类型">
                    <template slot-scope="scope">
                        <el-tag size="mini">@{{ scope.row.isYearManager ? '管理' : '任教' }}</el-tag>
                    </template>
                </el-table-column>
            </el-table>
        </div>
    </div>
@endsection
