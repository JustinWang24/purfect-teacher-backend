@extends('layouts.app')
@section('content')
    <div class="row" id="school-news-list-app">
        <div class="col-6">
            <div class="card">
                <div class="card-head">
                    <header>{{ session('school.name') }} - 校内通讯录</header>
                </div>
                <div class="card-body">
                    <el-table
                            :data="contacts"
                            stripe
                            style="width: 100%">
                        <el-table-column
                                prop="name"
                                label="机构名称"
                                width="200">
                        </el-table-column>
                        <el-table-column
                                prop="tel"
                                label="电话号码"
                                width="200">
                        </el-table-column>
                        <el-table-column
                                prop="address"
                                label="地址">
                        </el-table-column>
                    </el-table>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="card">
                <div class="card-head">
                    <header>
                        班级通讯录&nbsp;
                        <el-select v-model="gradeId" filterable placeholder="请选择">
                            <el-option
                                    v-for="grade in grades"
                                    :key="grade.id"
                                    :label="grade.name"
                                    :value="grade.id">
                            </el-option>
                        </el-select>
                    </header>
                </div>
                <div class="card-body">
                    <el-table
                            v-if="teachers.length > 0"
                            :data="teachers"
                            stripe
                            style="width: 100%">
                        <el-table-column
                                prop="type"
                                label="职务"
                                width="200">
                        </el-table-column>
                        <el-table-column
                                prop="name"
                                label="老师姓名">
                        </el-table-column>
                        <el-table-column
                                prop="tel"
                                label="电话号码"
                                width="200">
                        </el-table-column>
                    </el-table>
                    <h3 class="mt-4" v-if="mates.length > 0">学生联系方式: </h3>
                    <el-table
                            v-if="mates.length > 0"
                            :data="mates"
                            stripe
                            style="width: 100%">
                        <el-table-column
                                prop="type"
                                label="职务"
                                width="200">
                        </el-table-column>
                        <el-table-column
                                prop="name"
                                label="姓名">
                        </el-table-column>
                        <el-table-column
                                prop="tel"
                                label="电话号码"
                                width="200">
                        </el-table-column>
                    </el-table>
                </div>
            </div>
        </div>
    </div>
    <div id="app-init-data-holder"
         data-school="{{ session('school.uuid') }}"
         data-id="{{ session('school.id') }}"
    ></div>
@endsection
