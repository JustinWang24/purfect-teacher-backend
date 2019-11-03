<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
use App\User;
?>
@extends('layouts.app')
@section('content')
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
                            school-id="{{ session('school.id') }}"
                            :year="year"
                            :can-delete="{{ Auth::user()->isSchoolAdminOrAbove() ? 'true' : 'false' }}"
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
                            school-id="{{ session('school.id') }}"
                            :years="years"
                            :form="form"
                    ></recruitment-plan-form>
                </div>
            </div>
        </div>
    </div>
@endsection