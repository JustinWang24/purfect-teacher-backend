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
                            height="600"
                            v-show="classData.length > 0"
                            :show-header="false"
                            :data="classData">
                        <el-table-column
                                prop="name"
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
                    <div v-show="classData.length == 0" class="no-data-img">
                        <img src="{{ asset('assets/img/teacher_blade/no-data.png') }}" alt="">
                        <p>当前列表暂时没有数据哦~</p>
                    </div>
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
                            height="600"
                            v-show="stuData.length > 0"
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
                    <el-pagination
                      background
                      style="text-align: right;"
                      v-if="detailPage"
                      layout="prev, pager, next"
                      :page-size="20"
                      :current-page.sync="detailPage.currentPage"
                      @current-change="detailChange"
                      :total="detailPage.total">
                    </el-pagination>
                    <div v-show="stuData.length == 0" class="no-data-img">
                        <img src="{{ asset('assets/img/teacher_blade/no-data.png') }}" alt="">
                        <p>当前列表暂时没有数据哦~</p>
                    </div>
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
                                height="580"
                                :show-header="false"
                                :data="detailDataList">
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
                @{{detailData.name}}
            </el-form-item>
            <el-form-item label="身份证号">
                @{{detailData.id_number}}
            </el-form-item>
            <el-form-item label="性别">
                @{{detailData.gender}}
            </el-form-item>
            <el-form-item label="出生日期">
                @{{detailData.birthday}}
            </el-form-item>
            <el-form-item label="民族">
                @{{detailData.nation_name}}
            </el-form-item>
            <el-form-item label="政治面貌">
                @{{detailData.political_name}}
            </el-form-item>
            <el-form-item label="生源地">
                @{{detailData.source_place}}
            </el-form-item>
            <el-form-item label="籍贯">
                @{{detailData.country}}
            </el-form-item>
            <el-form-item class="can-edit" label="联系电话(可修改)">
                <el-input v-model="detailForm.contact_number"></el-input>
            </el-form-item>
            <el-form-item class="can-edit" label="QQ号(可修改)">
                <el-input v-model="detailForm.qq"></el-input>
            </el-form-item>
            <el-form-item class="can-edit" label="微信号(可修改)" style="border-bottom: 1px solid #ccc;">
                <el-input v-model="detailForm.wx"></el-input>
            </el-form-item>
            <el-form-item label="家长姓名">
                @{{detailData.parent_name}}
            </el-form-item>
            <el-form-item class="can-edit" label="家长电话(可修改)">
                <el-input v-model="detailForm.parent_mobile"></el-input>
            </el-form-item>
            <el-form-item class="can-edit" label="所在城市(可修改)">
                <el-input v-model="detailForm.city"></el-input>
            </el-form-item>
            <el-form-item class="can-edit" label="详细地址(可修改)">
                <el-input v-model="detailForm.address_line"></el-input>
            </el-form-item>
            <el-form-item class="can-edit" label="邮箱(可修改)" style="border-bottom: 1px solid #ccc;">
                <el-input v-model="detailForm.email"></el-input>
            </el-form-item>
            <el-form-item label="学制">
                 @{{detailData.school_year}}
            </el-form-item>
            <el-form-item label="学历">
                 @{{detailData.education}}
            </el-form-item>
            <el-form-item label="学院">
                 @{{detailData.institute}}
            </el-form-item>
            <el-form-item label="年级">
                 @{{detailData.year}}
            </el-form-item>
            <el-form-item label="专业">
                 @{{detailData.major}}
            </el-form-item>
            <el-form-item label="职务">
                <el-radio-group v-model="detailForm.position">
                      <el-radio label="monitor">班长</el-radio>
                      <el-radio label="group">团支书</el-radio>
                      <el-radio label="">无</el-radio>
                    </el-radio-group>
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
