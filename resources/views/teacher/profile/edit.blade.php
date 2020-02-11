<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>
@extends('layouts.app')
@section('content')
    <div class="row" id="teacher-profile-app-wrap">
        <div class="col-md-3">
            @include('teacher.elements.sidebar.avatar',['profile'=>$profile])
        </div>
        <div class="col-sm-12 col-md-6 col-xl-6">
            <div class="card">
                <div class="card-head">
                    <header class="full-width">
                        <span class="pull-left pt-2">{{ $teacher->name }}履历表</span>
                        @if(\Illuminate\Support\Facades\Auth::user()->isSchoolAdminOrAbove())
                        <a class="btn btn-primary btn-sm pull-right" href="{{ route('school_manager.teachers.manage-performance',['uuid'=>$teacher->uuid]) }}">
                            年终考评表
                        </a>
                        @endif
                    </header>
                </div>

                <div class="card-body">
                    @foreach($history as $performance)
                    <div class="performance-block">
                        <h4>{{ $performance->year }}年度工作评估: <span class="text-primary">{{ $performance->getResultText() }}</span></h4>
                        <h5>职称: {{ $performance->title }} - 部门: {{ $performance->organisation_name }}</h5>
                        <p class="text">
                            <span><b>评估报告</b></span>: {{ $performance->comments }}
                        </p>
                        <ul>
                        @foreach($performance->items as $item)
                            <li>
                                <div class="performance-item-block">
                                    <p><b>评估项目</b>: {{ $item->config->name }}</p>
                                    <p><b>评估结果</b>: {{ $item->comments }}</p>
                                    <p><b>总评</b>: {{ $item->getResultText() }}</p>
                                </div>
                            </li>
                        @endforeach
                        </ul>
                        <p class="text-right text-primary"><b>评估人</b>: {{ $performance->approvedBy->name }}</p>
                        <hr>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="card">
                <div class="card-head">
                    <header class="full-width">
                        <span class="pull-left pt-2">{{ $teacher->name }} 评聘佐证材料</span>
                        @if($school->configuration->open_for_uploading_qualification)
                        <el-button @click="showForm({{ $teacher->id }})" class="pull-right" size="small" type="primary">
                            添加教学业绩佐证材料
                        </el-button>
                        @endif
                    </header>
                </div>
                <div class="card-body">
                    <div v-show="showFormFlag">
                        <el-form ref="form" :model="qualification" label-width="120px">
                            <el-form-item label="标题">
                                <el-input v-model="qualification.title" placeholder="必填: 教学业绩的标题"></el-input>
                            </el-form-item>
                            <el-form-item label="分类">
                                <el-select v-model="qualification.type" placeholder="请选择分类">
                                    <el-option v-for="(theType, idx) in types" :key="idx" :label="theType" :value="idx"></el-option>
                                </el-select>
                            </el-form-item>
@php
$theYears = \App\Utils\Time\GradeAndYearUtil::GetAllYears(1);
@endphp
                            <el-form-item label="年度">
                                <el-select v-model="qualification.year" placeholder="请选择年度">
                                    @foreach($theYears as $year)
                                    <el-option label="{{ $year.'年' }}" value="{{ $year }}"></el-option>
                                    @endforeach
                                </el-select>
                            </el-form-item>

                            <el-form-item label="业绩描述">
                                <el-input type="textarea" v-model="qualification.desc" placeholder="必填: 教学业绩的详情描述"></el-input>
                            </el-form-item>

                            <el-form-item label="佐证材料">
                                <el-button type="primary" icon="el-icon-picture" v-on:click="showFileManagerFlag=true">
                                    添加佐证材料
                                </el-button>
                                <p v-if="qualification.path">
                                    佐证材料: <a :href="qualification.path" target="_blank">@{{ qualification.file_name }}</a>
                                </p>
                            </el-form-item>


                            <el-form-item>
                                <el-button type="primary" @click="onSubmit">立即创建</el-button>
                                <el-button @click="showFormFlag = false">取消</el-button>
                            </el-form-item>
                        </el-form>
                    </div>
                    @include(
                        'reusable_elements.section.file_manager_component',
                        ['pickFileHandler'=>'pickFileHandler']
                    )
                    <div v-show="qualifications.length > 0">
                        <el-table
                                :data="qualifications"
                                style="width: 100%">
                            <el-table-column
                                    prop="title"
                                    label="标题"
                                    width="180">
                            </el-table-column>
                            <el-table-column
                                    prop="year"
                                    label="年度"
                                    width="80">
                            </el-table-column>
                            <el-table-column
                                    label="类型"
                                    width="180"
                            >
                                <template slot-scope="scope">
@{{ types[scope.row.type] }}
                                </template>
                            </el-table-column>
                            <el-table-column
                                    label="佐证材料"
                            >
                                <template slot-scope="scope">
                                    <a :href="scope.row.path" target="_blank">@{{ scope.row.file_name }}</a>
                                </template>
                            </el-table-column>
                            <el-table-column
                                    prop="status"
                                    label="审核状态"
                                    width="80">
                            </el-table-column>
                        </el-table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-3 col-xl-3">
            <div class="card">
                <div class="card-head">
                    <header>行政职务</header>
                </div>
                <div class="card-body " id="bar-parent">
                        @if($userOrganization)
                            <p>{{ $userOrganization->title }}</p>
                        @else
                            <p>还未设置任何职务</p>
                        <hr>
                            @if(\Illuminate\Support\Facades\Auth::user()->isSchoolAdminOrAbove())
                                <form action="" method="post">
                                    @csrf
                                    <input type="hidden" name="ug[id]" value="{{ $userOrganization->id??null }}">
                                    <div class="form-group">
                                        <label for="attendance-title-input">部门</label>
                                        <select class="form-control" name="ug[organization_id]" id="">
                                            @foreach($organizations as $org)
                                                <option {{ $org->id === ($userOrganization->organization_id??null) ? 'selected' : null }} value="{{ $org->id }}">{{ $org->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="task-detail">职务</label>
                                        <select class="form-control" name="ug[title_id]" id="">
                                            @foreach($titles as $tid=>$title)
                                                <option {{ $tid === ($userOrganization->title_id??null) ? 'selected' : null }} value="{{ $tid }}">{{ $title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <?php
                                    Button::Print(['id'=>'btn-create-attendance','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                                    ?>&nbsp;
                                    <?php
                                    Anchor::Print(['text'=>trans('general.return'),'href'=>route('school_manager.attendance.list'),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                                    ?>
                                </form>
                            @endif
                        @endif
                </div>
            </div>

            <div class="card">
                <div class="card-head">
                    <header>教学职务</header>
                </div>
                <div class="card-body " id="bar-parent">
                    @foreach($groups as $group)
                    <p>
                        教研组: {{ $group->name }} ({{ $group->isLeader ? '组长' : '成员' }})
                    </p>
                    @endforeach
                    @if(count($groups) === 0)
                        <p>未在任何教研组任职 <a href="{{ route('school_manager.organizations.teaching-and-research-group') }}">(加入教研组)</a></p>
                    @endif
                    <hr>

                    @if($yearManager)
                    <p>担任{{ $yearManager->year }}年级组长</p>
                    @endif

                    @if($gradeManager)
                        <p>担任 <a href="{{ route('teacher.grade.users',['by'=>'grade','uuid'=>$gradeManager->grade_id,'type'=>\App\User::TYPE_STUDENT]) }}">{{ $gradeManager->grade->name }}</a> 的班主任</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div id="app-init-data-holder"
         data-user="{{ $teacher->id }}"
    ></div>
@endsection
