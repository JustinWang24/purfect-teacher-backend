<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>
@extends('layouts.app')
@section('content')
 <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12" id="application-type-app">
            <div class="card">
                <div class="card-head">
                    <header>添加</header>
                </div>
                <div class="card-body">
                    <el-form ref="type" :model="type" label-width="80px" action="{{ route('school_manager.students.applications-set-save') }}" method="post">
                        <el-form-item label="活动名称">
                            <el-input v-model="type.name"></el-input>
                        </el-form-item>
                        <el-form-item label="选择图标">
                            <el-button type="primary" icon="el-icon-picture" v-on:click="showFileManagerFlag=true">选择申请图标</el-button>
                            <p v-if="selectedImgUrl" class="mt-4">
                                <img :src="selectedImgUrl" width="200">
                            </p>
                        </el-form-item>
                        <el-form-item>
                            <el-button type="primary" @click="onSubmit">立即创建</el-button>
                            <?php
                            Anchor::Print(['text'=>trans('general.return'),'href'=>url()->previous(),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                            ?>
                        </el-form-item>
                    </el-form>
                </div>
            </div>
            @include(
                'reusable_elements.section.file_manager_component',
                ['pickFileHandler'=>'pickFileHandler']
            )
        </div>
 </div>

@endsection
