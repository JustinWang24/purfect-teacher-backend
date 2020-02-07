@php
/**
* @var \App\Models\Course $course
*/
$courseTeacher = $course->getCourseTeacher($teacher->id);
@endphp
@extends('layouts.app')
@section('content')
    <div class="row" id="course-materials-manager-app">
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h2>{{ $course->name }} ({{ $course->duration }}课时)</h2>
                    <hr>
                    <p>
                        <el-button type="text" @click="showNotesEditor">
                            @{{ showEditor ? '关闭说明信息编辑器' : '编辑说明信息' }}
                        </el-button>
                    </p>
                    <div v-show="!showEditor">
                        {!! $courseTeacher->teacher_notes !!}
                    </div>
                    <div v-show="showEditor">
                        <Redactor v-model="notes.teacher_notes" placeholder="请输入内容" :config="configOptions" />
                        @{{ notes.teacher_notes }}
                    </div>
                    <hr>
                    <el-timeline>
                        @foreach(range(1, $course->duration) as $idx)
                        <el-timeline-item timestamp="第{{ $idx }}课" placement="top">
                            <el-card>
                                @foreach($materials as $courseMaterial)
                                    @if($idx === $courseMaterial->index)
                                    <p>
                                        <el-tag size="small" type="{{ \App\Models\Courses\CourseMaterial::GetTypeTagClass($courseMaterial->type) }}">
                                            {{ \App\Models\Courses\CourseMaterial::GetTypeText($courseMaterial->type) }}
                                        </el-tag>
                                        @if($courseMaterial->media_id === 0)
                                            <el-tag size="small">
                                                外部链接
                                            </el-tag>
                                        @endif
                                        <span>
                                            <a href="{{ $courseMaterial->url }}" target="_blank">
                                                {{ $courseMaterial->description }}
                                            </a>
                                        </span>
                                    </p>
                                    <p style="font-size: 10px;color: #cccccc;" class="text-right">
                                        上传于{{ _printDate($courseMaterial->created_at) }} &nbsp;
                                        <el-button type="text" @click="deleteMaterial({{ $courseMaterial->id }})">
                                            <span class="text-danger">删除</span>
                                        </el-button>
                                        <el-button type="text" @click="editMaterial({{ $courseMaterial->id }})">
                                            <span>修改</span>
                                        </el-button>
                                    </p>
                                    <hr style="margin-top: 3px;">
                                    @endif
                                @endforeach
                                <p class="text-right">
                                    <el-button size="mini" @click="addMaterial({{ $idx }})">添加课件</el-button>
                                </p>
                            </el-card>
                        </el-timeline-item>
                        @endforeach
                    </el-timeline>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="card">
                <div class="card-body" v-show="showMaterialForm">
                    <el-form :model="courseMaterialModel" label-width="120px" class="course-form" style="margin-top: 20px;">
                        <el-form-item label="第几次课">
                            <el-select v-model="courseMaterialModel.index" placeholder="请选择第几次课">
                                @foreach(range(1, $course->duration) as $index)
                                    <el-option label="第{{ $index }}课" :value="{{ $index }}"></el-option>
                                @endforeach
                            </el-select>
                        </el-form-item>

                        <el-form-item label="课件类型">
                            <el-select v-model="courseMaterialModel.type" placeholder="请选择课件类型">
                                @foreach(\App\Models\Courses\CourseMaterial::AllTypes() as $key => $txt)
                                    <el-option label="{{ $txt }}" :value="{{ $key }}"></el-option>
                                @endforeach
                            </el-select>
                        </el-form-item>


                        <el-form-item label="课件描述">
                            <el-input placeholder="选填: 课件的描述" type="textarea" v-model="courseMaterialModel.description"></el-input>
                        </el-form-item>

                        <el-form-item label="外部链接">
                            <el-input placeholder="选填: 外部引用的URL链接地址" type="textarea" v-model="courseMaterialModel.url"></el-input>
                        </el-form-item>

                        <el-form-item label="选择课件文件">
                            <el-button type="primary" size="tiny" icon="el-icon-picture" v-on:click="showFileManagerFlag=true">
                                从我的云盘添加
                            </el-button>
                            <p v-if="selectedFile" class="mt-4">
                                已选择的文件:
                                <a :href="selectedFile.url">
                                    @{{ selectedFile.description }}
                                </a>
                                &nbsp;
                                <el-button type="text" @click="selectedFile = null"><span class="text-danger">放弃</span></el-button>
                            </p>
                        </el-form-item>
                        <p class="text-danger text-right">注意: 课件只能是外部链接或者云盘文件中的一种</p>
                        <el-button style="margin-left: 10px;" size="small" type="success" @click="submitUpload">保存</el-button>
                    </el-form>
                </div>
            </div>
        </div>
        @include(
                'reusable_elements.section.file_manager_component',
                ['pickFileHandler'=>'pickFileHandler']
            )
    </div>
    <div id="app-init-data-holder"
         data-school="{{ session('school.id') }}"
         data-course='{!! $course !!}'
         data-teacher='{!! $teacher !!}'
         data-notes='{!! $courseTeacher !!}'
    ></div>
@endsection
