@extends('layouts.app')
@section('content')
<div id="pipeline-flows-manager_form-app">
<el-row :gutter="20">
    <el-col :span="8">
        <div class="card">
            <div class="card-head">
                自定义控件
            </div>
            <div class="card-body clearfix">
                <div class="formItems">
                    <div class="items" v-for="item in itemMap" @click="clickItem(item)">
                        <span v-html="item.name"></span>
                        <span class="itemIcon">
                            <span :class="item.icon"></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </el-col>
    <el-col :span="8">
        <div class="card">
            <div class="card-head">
                页面预览
            </div>
            <div class="card-body">
                <div class="view">
                    <div class="viewBg">
                        <div class="viewContent">
                            <el-form ref="form" label-width="80px">
                                <el-form-item :prop="item.type" :rules="rules[item.type]" v-for="(item, index) in formList" :key="index" :label="item.title">
                                    <el-input v-model="item.value" v-if="item.type == 'input' || item.type == 'number' || item.type == 'money'" :placeholder="item.tips"></el-input>
                                    <el-input @input="textareaInput" v-model="item.value" v-if="item.type == 'textarea'" class="ipt-textarea" :placeholder="item.tips"></el-input>
                                    <el-radio v-model="item.value" v-if="item.type == 'radio'"  v-for="(itemRadio, indexRadio) in item.extra.itemList"
                                               :key="indexRadio" :label="itemRadio.itemText"></el-radio>
                                    <el-checkbox v-model="item.value" v-if="item.type == 'checkBox'"  v-for="(itemCheck, indexCheck) in item.extra.itemList"
                                                  :key="indexCheck" :label="itemCheck.itemText"></el-checkbox>
                                    <el-date-picker v-model="item.value"
                                            v-if="item.type == 'date'"
                                            :type="item.extra.dateType == 1?'datetime':'date'"
                                            :placeholder="item.tips">
                                    </el-date-picker>
                                    <el-date-picker v-model="item.value" style="width:100%"
                                            v-if="item.type == 'date-date'"
                                            :type="item.extra.dateType == 1?'datetimerange':'daterange'"
                                            :start-placeholder="item.title"
                                            :end-placeholder="item.title2">
                                    </el-date-picker>
                                    <el-upload disabled v-if="item.type == 'image'"
                                            class="avatar-uploader"
                                            :show-file-list="false">
                                        <img v-if="imageUrl" :src="imageUrl" class="avatar">
                                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                                    </el-upload>
                                    <el-upload
                                            disabled
                                            v-if="item.type == 'files'"
                                            class="upload-demo"
                                            multiple>
                                        <el-button size="small" type="primary">点击上传</el-button>
                                    </el-upload>
                                    <el-select v-if="item.type == 'department'"
                                               v-model="item.value"
                                               multiple
                                               filterable
                                               remote
                                               reserve-keyword
                                               :placeholder="item.tips">
                                    </el-select>
                                    <el-select v-if="item.type == 'area'"
                                               v-model="item.value"
                                               multiple
                                               filterable
                                               remote
                                               reserve-keyword
                                               :placeholder="item.tips">
                                    </el-select>
                                    <div class="nodeText" v-if="item.type == 'node'" v-html="item.nodeText"></div>
                                </el-form-item>
                            </el-form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </el-col>
    <el-col :span="8">
        <div class="card">
            <div class="card-head">
                设置表单项
            </div>
            <div class="card-body">
                <div class="formItem" v-for="(item, index) in formList">
                    <div class="formItemName">
                        <span v-html="item.name"></span>
                        <span style="float: right;" class="el-icon-close" @click="removeItem(index)"></span>
                    </div>
                    <!--<div class="formItemInput">-->
                    <!--<el-input v-model="" placeholder="请输入内容"></el-input>-->
                    <!--</div>-->
                    <div class="formItemTitel2" v-if="item.type != 'node'">
                        <div>
                            标题 <span class="status_gray">最多10个字</span>
                        </div>
                        <div>
                            <el-input v-model="item.title" maxlength="10" :placeholder="item.title_holder"></el-input>
                        </div>
                    </div>
                    <div v-if="item.type == 'date-date'" class="formItemTitel2">
                        <div>
                            标题2 <span class="status_gray">最多10个字</span>
                        </div>
                        <div>
                            <el-input v-model="item.title2" maxlength="10" :placeholder="item.title_holder2"></el-input>
                        </div>
                    </div>
                    <div class="formItemNode" v-if="item.type != 'image' && item.type != 'node'">
                        <div>
                            提示文字 <span class="status_gray">最多15个字</span>
                        </div>
                        <div class="">
                            <el-input v-model="item.tips" maxlength="15" :placeholder="item.tips_holder"></el-input>
                        </div>
                    </div>
                    <div class="formItemNode" v-if="item.type == 'node'">
                        <div>
                            说明文字 <span class="status_gray">最多500个字</span>
                        </div>
                        <div class="">
                            <el-input @input="textareaInput" type="textarea" maxlength="500" v-model="item.nodeText"></el-input>
                        </div>
                    </div>
                    <div class="formItemextra">
                        <div v-if="item.extra.text" v-html="item.extra.text"></div>
                        <div v-if="item.type == 'number'">
                            小数
                            <el-checkbox v-model="item.extra.floats">支持</el-checkbox>
                        </div>
                        <div v-if="item.type == 'radio' || item.type == 'checkBox'">
                            <div>
                                选项 <span class="status_gray">最多20个字</span>
                            </div>
                            <div>
                                <div class="radioItem" v-for="(item2, index2) in item.extra.itemList">
                                    <el-input maxlength="20" v-model="item2.itemText"></el-input>
                                    <div class="inline">
                                        <span @click="addRadioItem(item.extra.itemList, index2)"
                                              class="radioItemIcon el-icon-circle-plus" circle></span>
                                        <span @click="removeRadioItem(item.extra.itemList, index2)"
                                              v-show="index2 != 0" class="radioItemIcon el-icon-remove" circle></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="item.type == 'date' || item.type == 'date-date'">
                            <div>
                                日期类型
                            </div>
                            <div>
                                <el-radio v-model="item.extra.dateType" :label="1">年-月-日 时:分</el-radio>
                                <el-radio v-model="item.extra.dateType" :label="2">年-月-日</el-radio>
                            </div>
                        </div>
                        <div v-if="item.type == 'department'">
                            <div>
                                选项
                            </div>
                            <div>
                                <el-radio v-model="item.extra.depType" :label="1">只能选一个部门</el-radio>
                                <el-radio v-model="item.extra.depType" :label="2">可同时选择多个部门</el-radio>
                            </div>
                        </div>
                    </div>
                    <div class="formItemVer">
                        验证
                        <el-checkbox v-model="item.ver">必填</el-checkbox>
                    </div>
                </div>
            </div>
        </div>
    </el-col>
</el-row>
</div>
<div id="app-init-data-holder" data-school="{{ session('school.id') }}" data-newflow="{{ $lastNewFlow }}"></div>
@endsection
