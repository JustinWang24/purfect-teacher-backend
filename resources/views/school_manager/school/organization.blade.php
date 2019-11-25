@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12" id="organization-app">
            <div class="card">
                <div class="card-head">
                    <header>
                        {{ session('school.name') }} 组织机构
                        <button v-on:click="showForm" class="btn btn-sm btn-primary">添加新机构</button>
                    </header>
                </div>
                <div class="card-body">
                    <div class="organization-wrap">
                        <div class="level-row">
                            <div class="org">
                                {!! $root->output() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <el-dialog title="收货地址" :visible.sync="dialogFormVisible">
                <el-form :model="form">
                    <el-form-item label="组织机构级别" label-width="120px">
                        <el-select v-model="form.level" placeholder="请选择要创建的组织机构级别" style="width: 90%;">
                            @foreach(range(1, 4) as $item)
                            <el-option label="{{ $item }}级机构" value="{{ $item }}"></el-option>
                            @endforeach
                        </el-select>
                    </el-form-item>

                    <el-form-item label="上级组织"  label-width="120px">
                        <el-select v-model="form.parent_id" placeholder="请选择上级机构, 留空表示为无上级"  style="width: 90%;">
                            <el-option :key="idx" :label="p.name" :value="p.id" v-for="(p, idx) in parents"></el-option>
                        </el-select>
                    </el-form-item>

                    <el-form-item label="机构名称"  label-width="120px">
                        <el-input v-model="form.name" autocomplete="off"></el-input>
                    </el-form-item>

                </el-form>
                <div slot="footer" class="dialog-footer">
                    <el-button @click="dialogFormVisible = false">取 消</el-button>
                    <el-button type="primary" @click="saveOrg">确 定</el-button>
                </div>
            </el-dialog>
        </div>
    </div>
    <div id="app-init-data-holder"
         data-school="{{ session('school.id') }}"
         data-level="4"
    ></div>
@endsection
