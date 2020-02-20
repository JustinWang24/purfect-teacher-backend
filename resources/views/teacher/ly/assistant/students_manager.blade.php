@extends('layouts.app')
@section('content')
<div id="teacher-assistant-students-manager-app">
    <div class="blade_title">学生信息</div>
    <el-row :gutter="20">
        <el-col :span="8">
            <div class="grid-content bg-purple-dark"></div>
            <div class="card">
                <div class="card-head">
                    <header class="full-width">
                        班级列表
                    </header>
                </div>
                <div class="card-body">
                    <el-table
                            :show-header="false"
                            :data="classData">
                        <el-table-column
                                prop="class"
                                label="班级">
                        </el-table-column>
                        <el-table-column
                                label="详情">
                            <template slot-scope="scope">
                                <span class="showDetail">
                                     <img @click="showStu(scope.row)"
                                          src="{{ asset('assets/img/teacher_blade/eye.png') }}" class="icon-image">
                                </span>
                            </template>
                        </el-table-column>
                    </el-table>
                </div>
            </div>
        </el-col>
        <el-col :span="8" v-if="ifShowStu">
            <div class="grid-content bg-purple-dark"></div>
            <div class="card">
                <div class="card-head">
                    <header class="full-width">
                        学生明细
                    </header>
                </div>
                <div class="card-body">
                    <el-table
                            :show-header="false"
                            :data="stuData">
                        <el-table-column
                                prop="name"
                                label="姓名">
                        </el-table-column>
                        <el-table-column
                                label="详情">
                            <template slot-scope="scope">
                                <span class="showDetail">
                                     <img @click="showDetail(scope.row)"
                                          src="{{ asset('assets/img/teacher_blade/eye.png') }}" class="icon-image">
                                </span>
                            </template>
                        </el-table-column>
                    </el-table>
                </div>
            </div>
        </el-col>
        <el-col :span="8">
            <div class="stu-table3 grid-content bg-purple-dark" v-show="ifShowDetail">
                <div class="card">
                    <div class="card-head">
                        <header class="full-width">
                            学生信息
                        </header>
                    </div>
                    <div class="card-body">
                        <el-table
                                :show-header="false"
                                :data="detailData">
                            <el-table-column
                                    prop="label">
                            </el-table-column>
                            <el-table-column
                                    prop="detail">
                            </el-table-column>
                        </el-table>
                        <div style="text-align: center;padding:10px">
                            <el-button class="stu-edit" @click="dialogVisible = true">编辑</el-button>
                        </div>
                    </div>
                </div>
            </div>
        </el-col>
    </el-row>
    <el-dialog title="编辑学生信息"
               :visible.sync="dialogVisible"
               top="10vh"
               custom-class="stu-dialog">
        <el-form ref="form" :model="detailForm" label-width="80px">
            <el-form-item label="姓名" style="border-top: 1px solid #ccc;">
               张三
            </el-form-item>
            <el-form-item label="身份证号">
               xxx
            </el-form-item>
            <el-form-item label="性别">
               男
            </el-form-item>
            <el-form-item label="出生日期">
               1906
            </el-form-item>
            <el-form-item label="民族">
               汉族
            </el-form-item>
            <el-form-item label="政治面貌">
               党员
            </el-form-item>
            <el-form-item label="生源地">
               广东
            </el-form-item>
            <el-form-item label="籍贯">
               广东
            </el-form-item>
            <el-form-item class="can-edit" label="联系电话(可修改)">
                <el-input v-model="detailForm.tel"></el-input>
            </el-form-item>
            <el-form-item class="can-edit" label="QQ号(可修改)">
                <el-input v-model="detailForm.qq"></el-input>
            </el-form-item>
            <el-form-item class="can-edit" label="微信号(可修改)" style="border-bottom: 1px solid #ccc;">
                <el-input v-model="detailForm.vx"></el-input>
            </el-form-item>
            <el-form-item label="家长姓名">
                老王
            </el-form-item>
            <el-form-item class="can-edit" label="家长电话(可修改)">
                <el-input v-model="detailForm.parentTel"></el-input>
            </el-form-item>
            <el-form-item class="can-edit" label="所在城市(可修改)">
                <el-input v-model="detailForm.city"></el-input>
            </el-form-item>
            <el-form-item class="can-edit" label="详细地址(可修改)">
                <el-input v-model="detailForm.address"></el-input>
            </el-form-item>
            <el-form-item class="can-edit" label="邮箱(可修改)" style="border-bottom: 1px solid #ccc;">
                <el-input v-model="detailForm.email"></el-input>
            </el-form-item>
            <el-form-item label="学制">
               4
            </el-form-item>
            <el-form-item label="学历">
               本科
            </el-form-item>
            <el-form-item label="学院">
               国际贸易学院
            </el-form-item>
            <el-form-item label="年级">
               19级
            </el-form-item>
            <el-form-item label="专业">
               国际金融
            </el-form-item>
            <el-form-item label="职务">
               班长
            </el-form-item>
        </el-form>
        <div class="dialog-footer">
            <el-button type="primary" @click="onSubmit">保存</el-button>
        </div>
    </el-dialog>
</div>

<div id="app-init-data-holder"
     data-school="{{ session('school.id') }}"
></div>
@endsection
