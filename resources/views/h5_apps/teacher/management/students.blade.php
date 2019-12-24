@extends('layouts.h5_teacher_app')
@section('content')
    <div id="app-init-data-holder" data-school="{{ $teacher->getSchoolId() }}" data-grade="{{ json_encode($grade) }}"
         data-students="{{ json_encode($students) }}" data-token="{{ $api_token }}"></div>
    <div id="school-teacher-management-students-app" class="school-intro-container">
        <div class="main p-15">
            <h4>
                <el-button type="text" icon="el-icon-arrow-left" class="text-dark" @click="back"></el-button>&nbsp;
                班级: {{ $grade->name }}
            </h4>
            <el-table
                    :data="students"
                    empty-text="没有学生记录"
                    stripe
                    style="width: 100%">

                <el-table-column label="照片">
                    <template slot-scope="scope">
                        <img :src="scope.row.student_profile ? scope.row.student_profile.avatar : null" style="width: 60px; border-radius: 50%;">
                    </template>
                </el-table-column>

                <el-table-column
                        label="学生姓名">
                    <template slot-scope="scope">
                        <el-button @click="showDetail(scope.row)" type="text">@{{ scope.row.name }}</el-button>
                    </template>
                </el-table-column>

            </el-table>
        </div>
    </div>
@endsection
