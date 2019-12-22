<?php
    use App\Utils\UI\Anchor;
    use App\Utils\UI\Button;
    $years = \App\Utils\Time\GradeAndYearUtil::GetAllYears();
    $year = $years[count($years)-1];
    $ago = $years[0];
?>
@extends('layouts.app')
@section('content')
    <div id="app-init-data-holder"
         data-manager="{{ json_encode($yearManager??null) }}"
         data-school="{{ session('school.id') }}">
    </div>
    <div class="row" id="year-manager-setting-app">
        <div class="col-sm-12 col-md-6 col-xl-6">
            <div class="card">
                <div class="card-head">
                    <header>
                        {{ session('school.name') }} - 年级组长管理
                    </header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <el-form ref="form" :model="form" label-width="80px">
                            <el-form-item label="年级">
                                <el-select v-model="form.year" placeholder="请选择年级" style="width: 100%;">
                                    @foreach(range($ago, $year) as $year)
                                    <el-option label="{{ $year.'年级' }}" :value="{{ $year }}"></el-option>
                                    @endforeach
                                </el-select>
                            </el-form-item>
                            <el-form-item label="年级组长">
                                <el-select
                                        style="width:100%;"
                                        v-model="form.user_id"
                                        filterable
                                        allow-create
                                        default-first-option
                                        placeholder="请选择">
                                    @foreach($teachers as $teacher)
                                    <el-option label="{{ $teacher->name }}" :value="{{ $teacher->id }}"></el-option>
                                    @endforeach
                                </el-select>
                            </el-form-item>
                            <el-form-item>
                                <el-button type="primary" @click="onSubmit">保存</el-button>
                                <el-button type="success" @click="newManager">新建</el-button>
                            </el-form-item>
                        </el-form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-xl-6">
            <div class="card">
                <div class="card-head">
                    <header>
                        {{ session('school.name') }} - 年级组长管理
                    </header>
                </div>
                <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>年级</th>
                        <th>年级主任</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($managers as $index=>$manager)
                    <tr>
                        <td>{{ $index+1 }}</td>
                        <td>{{ $manager->year }}年</td>
                        <td>
                            {{ $manager->user->name }}
                        </td>
                        <td>
                            {{ Anchor::Print(['text'=>'编辑','class'=>'btn-edit-grade','href'=>route('school_manager.school.set-year-manager',['year'=>$manager->year])], Button::TYPE_DEFAULT,'edit') }}
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            </div>
            </div>
        </div>
    </div>
@endsection
