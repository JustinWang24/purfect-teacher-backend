@php
@endphp
@extends('layouts.app')
@section('content')
    <div id="app-init-data-holder"
         data-school="{{ session('school.id') }}"
         data-basics='{!! json_encode($basics) !!}'
    ></div>
    <div class="row" id="welcome-students-manager-app">
        <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
            <div class="card">
                <div class="card-head">
                    <header style="display: block">
                        <p class="pull-left mt-1">迎新流程</p>
                        <el-button size="mini" class="pull-right" type="primary" v-on:click="showStepSelector">添加迎新步骤</el-button>
                    </header>
                </div>
                <div class="card-body">
                    <p class="text-info">使用鼠标拖拽来调整顺序</p>
                    <el-timeline class="mt-4">
                        <el-timeline-item
                                v-for="(activity, index) in progress"
                                :key="index">
                            第 @{{ index+1 }} 步: @{{activity.name}}
                        </el-timeline-item>
                    </el-timeline>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="card">
                <div class="card-head">
                    <header>
                        <p class="pull-left mt-1">步骤详情</p>
                    </header>
                </div>
                <div class="card-body">
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
            <div class="card">
                <div class="card-head">
                    <header>
                        <p class="pull-left mt-1">步骤预览</p>
                    </header>
                </div>
                <div class="card-body">
                </div>
            </div>
        </div>

        <el-dialog title="添加步骤" :visible.sync="showStepSelectorFlag">
            <el-form :model="welcome">
                <el-form-item label="活动区域">
                    <el-select v-model="welcome.stepToBeAdd" placeholder="请选择">
                        <el-option
                                v-for="(basic, idx) in basics"
                                :key="idx"
                                :label="basic.name"
                                :value="basic.id"
                        ></el-option>
                    </el-select>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="showStepSelectorFlag = false">取 消</el-button>
                <el-button type="primary" @click="addNewStepAction">确 定</el-button>
            </div>
        </el-dialog>

    </div>
@endsection