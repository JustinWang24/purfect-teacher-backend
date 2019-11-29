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
            <el-dialog title="添加组织机构" :visible.sync="dialogFormVisible">
                <el-form :model="form">
                    <el-form-item label="组织机构级别" label-width="120px">
                        <el-select v-model="form.level" placeholder="请选择要创建的组织机构级别" style="width: 90%;">
                            @foreach(range(1, 4) as $item)
                            <el-option label="{{ $item }}级机构" :value="{{ $item }}"></el-option>
                            @endforeach
                        </el-select>
                    </el-form-item>

                    <el-form-item label="上级组织"  label-width="120px">
                        <el-select v-model="form.parent_id" placeholder="请选择上级机构, 留空表示为无上级"  style="width: 90%;">
                            <el-option :key="idx" :label="p.name" :value="p.id" v-for="(p, idx) in parents"></el-option>
                        </el-select>
                    </el-form-item>

                    <el-form-item label="机构名称" label-width="120px">
                        <el-input v-model="form.name" placeholder="必填"></el-input>
                    </el-form-item>

                    <el-form-item label="联系电话"  label-width="120px">
                        <el-input v-model="form.phone" placeholder="选填"></el-input>
                    </el-form-item>

                    <el-form-item label="地址"  label-width="120px">
                        <el-input v-model="form.address" placeholder="选填"></el-input>
                    </el-form-item>

                </el-form>
                <div slot="footer" class="dialog-footer">
                    <el-button @click="dialogFormVisible = false">取 消</el-button>
                    <el-button type="primary" @click="saveOrg">确 定</el-button>
                </div>
            </el-dialog>
            <el-drawer
                    title="组织机构"
                    :visible.sync="dialogEditFormVisible"
                    :before-close="handleClose"
                    direction="rtl"
                    ref="drawer"
                    size="70%"
            >
                <div class="demo-drawer__content">
                    <el-row>
                        <el-col :span="12" class="p-2">
                            <el-card class="box-card">
                                <h4>机构添加/编辑</h4>
                                <el-divider></el-divider>
                                <el-form :model="form">
                                    <el-row>
                                        <el-col :span="12">
                                            <el-form-item label="级别" label-width="70px">
                                                <el-select v-model="form.level" placeholder="请选择要创建的组织机构级别" style="width: 90%;">
                                                    @foreach(range(1, 4) as $item)
                                                        <el-option label="{{ $item }}级机构" :value="{{ $item }}"></el-option>
                                                    @endforeach
                                                </el-select>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="上级"  label-width="70px">
                                                <el-select v-model="form.parent_id" placeholder="请选择上级机构, 留空表示为无上级"  style="width: 90%;">
                                                    <el-option :key="idx" :label="p.name" :value="p.id" v-for="(p, idx) in parents"></el-option>
                                                </el-select>
                                            </el-form-item>
                                        </el-col>
                                    </el-row>
                                    <el-form-item label="机构名称" class="mr-4" label-width="120px">
                                        <el-input v-model="form.name" placeholder="必填"></el-input>
                                    </el-form-item>
                                    <el-form-item label="联系电话" class="mr-4" label-width="120px">
                                        <el-input v-model="form.phone" placeholder="选填"></el-input>
                                    </el-form-item>
                                    <el-form-item label="地址" class="mr-4" label-width="120px">
                                        <el-input v-model="form.address" placeholder="选填"></el-input>
                                    </el-form-item>
                                </el-form>
                                <div class="demo-drawer__footer">
                                    <el-button class="ml-4" @click="close">取 消</el-button>
                                    <el-button type="primary"
                                               @click="saveOrg" :loading="loading"
                                    >@{{ loading ? '通信中 ...' : '确 定' }}</el-button>
                                    <el-button class="pull-right mr-4" type="danger" @click="remove">删 除</el-button>
                                </div>
                            </el-card>
                        </el-col>
                        <el-col :span="12" class="p-2">
                            <el-card class="box-card">
                                <h4>人员构成</h4>
                                <el-divider></el-divider>
                                <search-bar
                                        :school-id="{{ session('school.id') }}"
                                        full-tip="输入名字, 添加成员"
                                        scope="employee"
                                        class="ml-4"
                                        :init-query="currentMember.name"
                                        v-on:result-item-selected="selectMember"
                                ></search-bar>
                                <el-form :model="currentMember" class="mt-4">
                                <el-row>
                                    <el-col :span="12">
                                        <el-form-item label="职务"  label-width="70px">
                                            <el-select v-model="currentMember.title_id" placeholder=""  style="width: 90%;">
                                                <el-option label="职员" :value="{{ \App\Utils\Misc\Contracts\Title::MEMBER }}"></el-option>
                                                <el-option label="副手" :value="{{ \App\Utils\Misc\Contracts\Title::DEPUTY }}"></el-option>
                                                <el-option label="主管" :value="{{ \App\Utils\Misc\Contracts\Title::LEADER }}"></el-option>
                                            </el-select>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="头衔" class="mr-4" label-width="70px">
                                            <el-input v-model="currentMember.title" placeholder="必填"></el-input>
                                        </el-form-item>
                                    </el-col>
                                </el-row>
                                    <el-form-item>
                                        <el-button type="primary" @click="addToOrg">保存</el-button>
                                    </el-form-item>
                                </el-form>
                                <div>
                                    <el-tag
                                            :key="idx"
                                            v-for="(member, idx) in members"
                                            class="mr-2"
                                            closable
                                            :disable-transitions="false"
                                            @click="editMember(member)"
                                            @close="removeFromOrg(member)">
                                        @{{  member.title }}: @{{ member.name }}
                                    </el-tag>
                                </div>
                            </el-card>
                        </el-col>
                    </el-row>
                </div>
            </el-drawer>
        </div>
    </div>
    <div id="app-init-data-holder"
         data-school="{{ session('school.id') }}"
         data-level="4"
    ></div>
@endsection
