@extends('layouts.app')
@section('content')
    <div id="course-materials-manager-app">
        <div class="row">
            <el-menu :default-active="activeIndex" class="el-menu-demo" mode="horizontal" @select="handleMenuSelect" style="margin: 0 auto;">
                <el-menu-item index="1">我的课程</el-menu-item>
                <el-menu-item index="2">课件管理</el-menu-item>
                <el-submenu index="3">
                    <template slot="title">学生管理</template>
                    @foreach($grades as $grade)
                    <el-menu-item index="2-{{ $grade['id'] }}">{{ $grade['name'] }}</el-menu-item>
                    @endforeach
                </el-submenu>
                <el-menu-item index="4"><a href="https://www.baidu.com" target="_blank">查看课表</a></el-menu-item>
            </el-menu>
        </div>

        <div class="row" v-show="activeIndex === '1'">
            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h2>{{ $course->name }} (总计{{ $course->duration }}课时)</h2>
                        <hr>
                        <h4>课程简介与教学计划:</h4>
                        <div v-if="notes && !showEditor">
                            <div v-html="notes.teacher_notes"></div>
                        </div>
                        <p class="mt-3">
                            <el-button type="text" @click="showNotesEditor">
                                @{{ showEditor ? '' : '编辑课程简介与教学计划' }}
                            </el-button>
                        </p>
                        <div v-show="notes && showEditor">
                            <Redactor v-model="notes.teacher_notes" placeholder="请输入内容" :config="configOptions" />
                            @{{ notes.teacher_notes }}
                        </div>
                        <div class="mt-3" v-show="showEditor">
                            <el-button type="primary" @click="saveNotes">保存/关闭</el-button>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h2>教学日志 <el-button class="pull-right" type="primary" size="small" icon="el-icon-edit" @click="showLogEditorHandler()">写日志</el-button></h2>
                        <hr>
                        <div v-show="showLogEditor">
                            <el-form :model="logModel" label-width="80px" class="course-form" style="margin-top: 20px;">
                                <el-form-item label="标题">
                                    <el-input placeholder="必填: 标题" v-model="logModel.title"></el-input>
                                </el-form-item>
                                <el-form-item label="标题">
                                    <el-input placeholder="必填: 日志内容" type="textarea" v-model="logModel.content"></el-input>
                                </el-form-item>
                                <el-button style="margin-left: 10px;" size="small" type="success" @click="saveLog">保存</el-button>
                                <el-button style="margin-left: 10px;" size="small" @click="showLogEditor=false">关闭</el-button>
                            </el-form>
                            <hr>
                        </div>

                        <el-card class="box-card mb-4" v-for="log in logs" :key="log.id" shadow="hover">
                            <div class="text item pb-3">
                                <h4>标题: @{{ log.title }}</h4>
                                <p style="color: #ccc; font-size: 10px;">最后更新于: @{{ log.updated_at ? log.updated_at : '刚刚' }}</p>
                                <p>内容: @{{ log.content }}</p>
                                <el-button style="float: left;" size="mini" type="primary" @click="showLogEditorHandler(log)">编辑</el-button>
                                <el-button style="float: right;" size="mini" type="danger" @click="deleteLog(log)">删除</el-button>
                            </div>
                        </el-card>
                    </div>
                </div>
            </div>
        </div>

        <div  v-show="activeIndex === '2'">
            <lecture :grades="grades" :lecture="lecture" v-if="lecture" :loading="loadingData" user-uuid="{{ $teacher->uuid }}"></lecture>
        </div>

        <div  v-show="activeIndex === '3'">
            <grade-table :grade="currentGradeId" teacher="{{ $teacher->id }}" course="{{ $course->id }}"></grade-table>
        </div>

        <el-dialog title="{{ $course->name }} ({{ $course->duration }}课时)" :visible.sync="courseIndexerVisible">
            <course-indexer :count="{{ $course->duration }}" :highlight="highlight" v-on:index-clicked="switchCourseIndex"></course-indexer>
        </el-dialog>

    </div>
    <div id="app-init-data-holder"
         data-school="{{ session('school.id') }}"
         data-course='{!! $course !!}'
         data-teacher='{!! $teacher !!}'
         data-grades='{!! json_encode($grades) !!}'
    ></div>
@endsection
