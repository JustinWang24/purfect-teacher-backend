<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
use App\User;
?>
@extends('layouts.app')
@section('content')
    <div id="plan-manager-school-id" data-id="{{ session('school.id') }}" data-uuid="{{ Auth::user()->uuid }}"></div>
    <div class="row" id="school-recruitment-manager-app">
        <div class="col-7">
            <div class="card-box">
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
                        :plans="plans"
                        :can-delete="{{ Auth::user()->isSchoolAdminOrAbove() ? 'true' : 'false' }}"
                        v-on:delete-plan="onDeletePlanHandler"
                        v-on:edit-plan="onEditPlanHandler"
                    ></recruitment-plans-list>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="card-box">
                <div class="card-head">
                    <header>招生计划</header>
                </div>
                <div class="card-body">
                    <recruitment-plan-form
                        :school-id="schoolId"
                        :years="years"
                        :form="form"
                        v-on:new-plan-created="newPlanCreatedHandler"
                        v-on:plan-updated="planUpdatedHandler"
                    ></recruitment-plan-form>
                </div>
            </div>
        </div>
    </div>
@endsection