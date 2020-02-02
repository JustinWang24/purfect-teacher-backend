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
        <span>
            <a href="{{ $courseMaterial->url }}">
                {{ $courseMaterial->description }}
            </a>
        </span>
        <span class="text-grey">上传于{{ _printDate($courseMaterial->created_at) }}</span>
    </p>
                                    @endif
                                @endforeach
                                <p class="text-right">
                                    <el-button size="mini" @click="addMaterial({{ $idx }})">添加课件</el-button>
                                    <el-button size="mini" type="primary" @click="loadDetail({{ $idx }})">查看详情</el-button>
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
                    <el-form :model="courseMaterialModel" label-width="80px" class="course-form" style="margin-top: 20px;">
                        <el-form-item label="课件类型">
                            <el-select v-model="courseMaterialModel.type" placeholder="请选择课件类型">
                                @foreach(\App\Models\Courses\CourseMaterial::AllTypes() as $key => $txt)
                                    <el-option label="{{ $txt }}" :value="{{ $key }}"></el-option>
                                @endforeach
                            </el-select>
                        </el-form-item>
                        <el-form-item label="第几次课">
                            <el-select v-model="courseMaterialModel.index" placeholder="请选择第几次课">
                                @foreach(range(1, $course->duration) as $index)
                                    <el-option label="第{{ $index }}课" :value="{{ $index }}"></el-option>
                                @endforeach
                            </el-select>
                        </el-form-item>
                        <el-form-item label="课件描述">
                            <el-upload
                                    class="upload-demo"
                                    ref="upload"
                                    action="useless"
                                    :before-upload="beforeFileUpload"
                                    :multiple="false">
                                <el-button slot="trigger" size="small" type="primary">选取文件</el-button>
                                <div slot="tip" class="el-upload__tip">只能上传jpg/png文件，且不超过500kb</div>
                            </el-upload>
                        </el-form-item>
                        <el-form-item label="课件描述">
                            <el-input placeholder="选填: 课件的描述" type="textarea" v-model="courseMaterialModel.description"></el-input>
                        </el-form-item>
                        <el-button style="margin-left: 10px;" size="small" type="success" @click="submitUpload">保存</el-button>
                    </el-form>
                </div>
            </div>
        </div>
    </div>
    <div id="app-init-data-holder"
         data-school="{{ session('school.id') }}"
         data-course='{!! $course !!}'
         data-teacher='{!! $teacher !!}'
         data-notes='{!! $courseTeacher !!}'
    ></div>
@endsection
