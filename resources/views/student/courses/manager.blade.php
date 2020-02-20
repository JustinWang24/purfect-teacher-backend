@extends('layouts.app')
@section('content')
    <div id="course-student-manager-app">
        <div class="row">
            <el-menu default-active="1" class="el-menu-demo" mode="horizontal" @select="handleMenuSelect" style="margin: 0 auto;">
                <el-menu-item index="1">选择课节</el-menu-item>
                <el-menu-item index="2"><a href="https://www.baidu.com" target="_blank">查看课表</a></el-menu-item>
            </el-menu>
        </div>

        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h2>{{ $course->name }} (总计{{ $course->duration }}课时)</h2>
                        <hr>
                        <div v-if="currentLecture">
                            <h3>第@{{ currentLecture.idx }}课: @{{ currentLecture.title }}</h3>
                            <el-timeline>
                                <el-timeline-item
                                        v-for="(type, typeIdx) in materialTypes"
                                        :key="typeIdx"
                                        :timestamp="type"
                                        placement="top"
                                        size="large"
                                        icon="el-icon-folder-opened"
                                >
                                    <el-card>
                                        <div v-for="material in currentMaterials" :key="material.id">
                                            <div v-if="isTypeOf(material.type, typeIdx)">
                                                <p>
                                                    <el-tag size="small" v-if="material.media_id === 0">
                                                        外部链接
                                                    </el-tag>
                                                    <span>
                                                        <a :href="material.url" target="_blank">
                                                            @{{ material.description }}
                                                        </a>
                                                    </span>
                                                </p>
                                                <p style="font-size: 10px;color: #cccccc;" class="text-right">
                                                    上传于@{{ material.created_at }}
                                                </p>
                                            </div>
                                        </div>
                                    </el-card>
                                </el-timeline-item>
                            </el-timeline>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h2>
                            我的作业
                            <el-button type="primary" size="small" @click="showSubmitHomeworkForm" class="pull-right">交作业</el-button>
                        </h2>
                        <hr>
                        <div v-show="showHomeworkForm">
                            <el-form ref="form" :model="homeworkModel" label-width="80px">
                                <el-form-item label="说明">
                                    <el-input type="textarea" placeholder="选填: 作业说明" v-model="homeworkModel.content"></el-input>
                                </el-form-item>
                                <el-form-item label="附件">
                                    <el-upload
                                            class="student-homework-files"
                                            ref="studentHomeworkRef"
                                            :headers="formHeaders"
                                            :action="homeworkSubmitUrl"
                                            :multiple="false"
                                            :with-credentials="true"
                                            :on-success="onHomeworkSubmittedHandler"
                                            :on-remove="onHomeworkRemovedHandler"
                                            :on-exceed="onHomeworkExceededHandler"
                                            :data="studentHomeworkFormData"
                                            :auto-upload="false">
                                        <el-button slot="trigger" size="small" type="primary">选取文件</el-button>
                                        <div slot="tip" class="el-upload__tip">单个附件文件大小不超过1兆</div>
                                    </el-upload>
                                </el-form-item>
                            <el-button style="margin-left: 10px;" size="small" type="success" icon="el-icon-upload" @click="submitUpload('form')">提交</el-button>
                            </el-form>
                        </div>
                        <el-table
                                :data="currentHomeworks"
                                empty-text="还没有交作业"
                                style="width: 100%">
                            <el-table-column
                                    label="提交日期"
                                    width="180">
                                <template slot-scope="scope">
                                    <i class="el-icon-time"></i>
                                    <span style="margin-left: 10px">@{{ scope.row.created_at }}</span>
                                </template>
                            </el-table-column>

                            <el-table-column
                                    label="作业">
                                <template slot-scope="scope">
                                    <p>
                                        <a target="_blank" :href="scope.row.url">@{{ scope.row.content }}</a>
                                    </p>
                                </template>
                            </el-table-column>

                            <el-table-column
                                    label="得分"
                                    width="80">
                                <template slot-scope="scope">
                                    @{{ scope.row.score > 0 ? scope.row.score : '未评分' }}
                                </template>
                            </el-table-column>

                            <el-table-column
                                    label="教师评语"
                                    prop="comment">
                            </el-table-column>

                            <el-table-column label="操作">
                                <template slot-scope="scope">
                                    <el-button
                                            v-if="scope.row.score<1"
                                            size="mini"
                                            type="danger"
                                            @click="deleteHomework(scope.$index, scope.row)">删除</el-button>
                                </template>
                            </el-table-column>
                        </el-table>
                    </div>
                </div>
            </div>
        </div>

        <el-dialog title="{{ $course->name }} ({{ $course->duration }}课时)" :visible.sync="courseIndexerVisible">
            <course-indexer :lectures="lectures" :count="{{ $course->duration }}" :highlight="highlight" v-on:lecture-clicked="switchLecture"></course-indexer>
        </el-dialog>
    </div>
    <div id="app-init-data-holder"
         data-school="{{ session('school.id') }}"
         data-course='{!! $course !!}'
         data-student='{!! $student !!}'
         data-lectures='{!! json_encode($lectures) !!}'
    ></div>
@endsection
