<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>

@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card" id="new-attendance-app">
                <div class="card-head">
                    <header>值周表</header>
                </div>
                <div class="card-body">
                    <el-form ref="form" :model="form" label-width="80px">
                        <el-form-item label="值班周次">
                            <el-select v-model="form.start_date" placeholder="请选择值班周次" style="width: 100%;">
                                @foreach($weeks as $week)
                                    <el-option label="{{ $week->getName().': 从'._printDate($week->getStart()).' 至 '._printDate($week->getEnd()) }}" value="{{ $week->getStart()->format('Y-m-d') }}"></el-option>
                                @endforeach
                            </el-select>
                        </el-form-item>

                        <el-form-item label="校领导">
                            <el-input v-model="form.high_level" placeholder="必填: 校领导"></el-input>
                        </el-form-item>
                        <el-form-item label="中层领导">
                            <el-input v-model="form.middle_level" placeholder="必填: 中层领导"></el-input>
                        </el-form-item>
                        <el-form-item label="责任教师">
                            <el-input v-model="form.teacher_level" placeholder="必填: 责任教师"></el-input>
                        </el-form-item>
                        <el-form-item label="责任班级">
                            <el-select v-model="form.grade_id" placeholder="请选择责任班级">
                                @foreach($grades as $grade)
                                <el-option label="{{ $grade->name }}" value="{{ $grade->id }}"></el-option>
                                @endforeach
                            </el-select>
                        </el-form-item>

                        <el-form-item label="组织单位">
                            <el-select
                                    style="width:100%;"
                                    v-model="form.related_organizations"
                                    multiple
                                    filterable
                                    allow-create
                                    default-first-option
                                    placeholder="请选择组织单位">
                                @foreach($orgs as $org)
                                    <el-option label="{{ $org->name }}" value="{{ $org->name }}"></el-option>
                                @endforeach
                            </el-select>
                        </el-form-item>

                        <el-form-item label="备注说明">
                            <el-input type="textarea" v-model="form.description" placeholder="选填: 值周的备注说明"></el-input>
                        </el-form-item>
                        <el-form-item>
                            <el-button type="primary" @click="onSubmit">立即创建</el-button>
                            <el-button>取消</el-button>
                        </el-form-item>
                    </el-form>
                </div>
            </div>
        </div>
    </div>
    <div id="app-init-data-holder"
         data-attendance="{{ json_encode($attendance??'') }}"
         data-school="{{ session('school.id') }}"
    </div>
@endsection
