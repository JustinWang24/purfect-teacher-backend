<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
use App\User;
?>
@extends('layouts.app')
@section('content')
    <span id="current-manager-uuid" data-id="{{ Auth::user()->uuid }}"></span>
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card">
                <div class="card-head">
                    <header><span class="text-primary">{{ $plan->title }}</span> - 报名表管理: (等待被批准的报名人数: {{ $registrations->count() }})</header>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="table-padding col-12">
                            <a href="{{ route('school_manager.student.add') }}" class="btn btn-primary">
                                帮学生报名 <i class="fa fa-plus"></i>
                            </a>
                            <div id="user-quick-search-app" class="pull-left mr-4">
                                <search-bar
                                        school-id="{{ session('school.id') }}"
                                        scope="registrations"
                                        full-tip="按姓名查找学生的报名表"
                                        v-on:result-item-selected="onItemSelected"
                                ></search-bar>
                            </div>
                        </div>
                        <div class="table-responsive" id="registration-forms-list-app">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>姓名</th>
                                    <th>身份证号</th>
                                    <th>生源</th>
                                    <th>联系方式</th>
                                    <th>中高考分数</th>
                                    <th>状态</th>
                                    <th>服从调剂</th>
                                    <th>备注</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($registrations as $form)
                                    @php
                                        /** @var \App\Models\RecruitStudent\RegistrationInformatics $form */
                                    @endphp
                                    <tr>
                                        <td>{{ $form->name }}</td>
                                        <td>{{ $form->profile->id_number}}</td>
                                        <td>{{ $form->profile->source_place }}</td>
                                        <td>
                                            <p>学生电话: {{ $form->user->mobile }}</p>
                                            <p>电子邮件: <a href="mailto:{{$form->user->email}}">{{ $form->user->email }}</a></p>
                                            <p>家长电话: {{ $form->profile->parent_name }} {{ $form->profile->parent_mobile }}</p>
                                        </td>
                                        <td>{{ $form->profile->examination_score }}</td>
                                        <td>
                                            {{ $form->getStatusText() }}
                                        </td>
                                        <td>
                                        @if($form->isRelocationAllowed())
                                            <span class="text-success">服从调剂</span>
                                        @else
                                            <span class="text-danger">不服从调剂</span>
                                        @endif
                                        </td>
                                        <td>{{ $form->note }}</td>
                                        <td class="text-center">
                                            <a target="_blank" href="{{ route('teacher.registration.view',['uuid'=>$form->profile->uuid,'plan'=>$plan->id]) }}" class="btn btn-round btn-primary btn-view-timetable">
                                                <i class="fa fa-edit"></i>查看详情
                                            </a>
                                            @if($form->status === \App\Models\RecruitStudent\RegistrationInformatics::WAITING)
                                                <el-button icon="el-icon-check" v-on:click="showNotesForm({{ $form->id }}, '{{ $form->name }}')">批准</el-button>
                                                <el-button type="danger" icon="el-icon-close" v-on:click="showRejectForm({{ $form->id }}, '{{ $form->name }}')">拒绝</el-button>
                                            @endif
                                            {{ Anchor::Print(['text'=>'删除','href'=>route('teacher.registration.delete',['uuid'=>$form->user_id])], Button::TYPE_DANGER,'trash') }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <el-dialog title="批准报名, 进入录取程序" :visible.sync="showNoteFormFlag">
                                <p>学生姓名: @{{ currentName }}</p>
                                <p>报名专业: {{ $plan->title }} - {{ $plan->year }}年</p>
                                <p class="text-info">一旦批准, 该学生在本校的所有其他报名申请将自动作废.</p>
                                <el-form :model="form">
                                    <el-form-item label="备注">
                                        <el-input type="textarea" v-model="form.note"></el-input>
                                    </el-form-item>
                                </el-form>
                                <div slot="footer" class="dialog-footer">
                                    <el-button @click="showNoteFormFlag = false">取 消</el-button>
                                    <el-button type="primary" @click="submit">确 定</el-button>
                                </div>
                            </el-dialog>

                            <el-dialog title="拒绝此报名申请" :visible.sync="rejectNoteFormFlag">
                                <p class="text-danger">学生姓名: @{{ currentName }}</p>
                                <p class="text-danger">报名专业: {{ $plan->title }} - {{ $plan->year }}年</p>
                                <el-form :model="form">
                                    <el-form-item label="拒绝此报名申请的原因">
                                        <el-input type="textarea" v-model="form.note"></el-input>
                                    </el-form-item>
                                </el-form>
                                <div slot="footer" class="dialog-footer">
                                    <el-button @click="rejectNoteFormFlag = false">取 消</el-button>
                                    <el-button type="primary" @click="submit">确 定</el-button>
                                </div>
                            </el-dialog>

                        </div>
                        <div class="row">
                            <div class="col-12">
                                {{ isset($appendedParams) ? $registrations->appends($appendedParams)->links() : $registrations->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
