<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
use App\User;
?>
@extends('layouts.app')
@section('content')
    <div class="row" id="school-recruitment-manager-app">
        <div class="col-6">
            <div class="card-box">
                <div class="card-head">
                    <header>
                        <span class="pull-left">{{ session('school.name') }}: 招生计划表</span>
                    </header>
                    <el-button type="primary" icon="el-icon-plus" class="pull-right m-2">添加新计划</el-button>
                </div>
                <div class="card-body">
                    <recruitment-plans-list
                            user-uuid="{{ Auth::user()->uuid }}"
                            school-id="{{ session('school.id') }}"
                            :can-delete="{{ Auth::user()->isSchoolAdminOrAbove() ? 'true' : 'false' }}"
                    ></recruitment-plans-list>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card-box">
                <div class="card-head">
                    <header>
                        招生计划:
                    </header>
                </div>
                <div class="card-body">
                    <recruitment-plan-form
                            user-uuid="{{ Auth::user()->uuid }}"
                            school-id="{{ session('school.id') }}"
                            :plan-id="0"
                            :form="form"
                    ></recruitment-plan-form>
                </div>
            </div>
        </div>
    </div>
@endsection