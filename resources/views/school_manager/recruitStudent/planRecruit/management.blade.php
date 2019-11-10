<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
use App\User;
?>
@extends('layouts.app')
@section('content')
    <div id="plan-manager-school-id" data-id="{{ session('school.id') }}" data-uuid="{{ Auth::user()->uuid }}"></div>
    <div id="school-recruitment-manager-app">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-head">
                        <header>
                            <span class="pull-left">{{ session('school.name') }}: 招生计划表</span>
                        </header>
                        <div class="pull-right m-2">
                        <el-select v-model="year" placeholder="请选择招生年份">
                            <el-option
                                    v-for="y in years"
                                    :key="y"
                                    :label="'招生年份: ' + y + '年'"
                                    :value="y">
                            </el-option>
                        </el-select>
                        <el-button v-on:click="createNewPlan" type="primary" icon="el-icon-plus">添加新计划</el-button>
                        </div>
                    </div>
                    <div class="card-body">
                        <recruitment-plans-list
                            :school-id="schoolId"
                            :user-uuid="userUuid"
                            :plans="plans"
                            :year="year"
                            :can-delete="{{ Auth::user()->isSchoolAdminOrAbove() ? 'true' : 'false' }}"
                            v-on:delete-plan="onDeletePlanHandler"
                            v-on:edit-plan="onEditPlanHandler"
                        ></recruitment-plans-list>
                    </div>
                </div>
            </div>
        </div>

        <el-dialog title="招生计划信息表" :visible.sync="showRecruitmentPlanFormFlag">
            <recruitment-plan-form
                    :school-id="schoolId"
                    :years="years"
                    :form="form"
                    :something-changed="flag"
                    v-on:new-plan-created="newPlanCreatedHandler"
                    v-on:plan-updated="planUpdatedHandler"
            ></recruitment-plan-form>
        </el-dialog>
    </div>
@endsection