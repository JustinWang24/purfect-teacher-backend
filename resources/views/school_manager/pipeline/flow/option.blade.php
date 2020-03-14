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
                                        <div class="fromItem" v-for="item in formList">
                                            <div v-if="item.type == 'input' || item.type=='textarea' || item.type=='number'">
                                                <van-field disabled
                                                        v-model="item.value"
                                                        :type="item.type"
                                                        :name="item.title"
                                                        :label="item.title"
                                                        :placeholder="item.tips"
                                                        :required="item.required?true:false"
                                                        :rules="item.required?[{ required: item.required,pattern: item.type=='number'?(item.extra.floats?/^[0-9]+(.[0-9]{2})?$/:/^(\-|\+)?\d*$/):'', message:item.type=='number'?(item.extra.floats?'支持输入两位小数':'请输入整数'): '请填写'+item.tips }]:[]"
                                                />
                                            </div>
                                            <div v-if="item.type == 'radio'">
                                                <van-field disabled :required="item.required?true:false" name="radio" :label="item.title">
                                                    <template #input>
                                                        <van-radio-group v-model="item.value" direction="horizontal">
                                                            <van-radio v-for="(item2, index2) in item.extra.itemList" :name="item2">@{{item2.itemText}}
                                                            </van-radio>
                                                        </van-radio-group>
                                                    </template>
                                                </van-field>
                                            </div>
                                            <div v-if="item.type == 'checkbox'">
                                                <van-field disabled :required="item.required?true:false" name="checkboxGroup" :label="item.title">
                                                    <template #input>
                                                        <van-checkbox-group v-model="item.value" direction="horizontal">
                                                            <van-checkbox v-for="(itemCB, indexCB) in item.extra.itemList" :name="itemCB" shape="square">
                                                                @{{itemCB.itemText}}
                                                            </van-checkbox>
                                                        </van-checkbox-group>
                                                    </template>
                                                </van-field>
                                            </div>
                                            <div v-if="item.type == 'date'">
                                                <van-field disabled
                                                        :required="item.required?true:false"
                                                        readonly
                                                        clickable
                                                        name="datetimePicker"
                                                        :value="item.value"
                                                        :label="item.title"
                                                        :placeholder="item.tips"
                                                />
                                                </van-field>
                                                <van-popup v-model="item.extra.showPicker" position="bottom">
                                                    <van-datetime-picker
                                                            v-model='item.time'
                                                            :min-date="minDate"
                                                            :max-date="maxDate"
                                                            @confirm="onConfirm(item)"
                                                            @cancel="item.extra.showPicker = false"
                                                            :type="item.extra.dateType==1?'datetime':'date'"
                                                    />
                                                </van-popup>
                                            </div>
                                            <div v-if="item.type == 'date-date'">
                                                <div>
                                                    <van-field disabled
                                                            :required="item.required?true:false"
                                                            readonly
                                                            clickable
                                                            name="datetimePicker"
                                                            :value="item.valueS"
                                                            :label="item.title"
                                                            :placeholder="item.tips"
                                                    />
                                                    </van-field>
                                                    <van-popup v-model="item.extra.showPickerS" position="bottom">
                                                        <van-datetime-picker
                                                                v-model='item.timeS'
                                                                :min-date="minDate"
                                                                :max-date="maxDate"
                                                                @confirm="onConfirmS(item)"
                                                                @cancel="item.extra.showPickerS = false"
                                                                :type="item.extra.dateType==1?'datetime':'date'"
                                                        />
                                                    </van-popup>
                                                </div>
                                                <div>
                                                    <van-field disabled
                                                            :required="item.required?true:false"
                                                            readonly
                                                            clickable
                                                            name="datetimePicker"
                                                            :value="item.valueE"
                                                            :label="item.extra.title2"
                                                            :placeholder="item.tips"

                                                    />
                                                    </van-field>
                                                    <van-popup v-model="item.extra.showPickerE" position="bottom">
                                                        <van-datetime-picker
                                                                v-model='item.timeE'
                                                                :min-date="minDate"
                                                                :max-date="maxDate"
                                                                @confirm="onConfirmE(item)"
                                                                @cancel="item.extra.showPickerE = false"
                                                                :type="item.extra.dateType==1?'datetime':'date'"
                                                        />
                                                    </van-popup>
                                                </div>
                                            </div>
                                            <div v-if="item.type == 'image' || item.type == 'files'">
                                                <van-field disabled name="uploader" :required="item.required?true:false" :label="item.title">
                                                    <template #input>
                                                        <van-uploader v-model="item.files" :max-count="9" :after-read="uploadImg" />
                                                    </template>
                                                </van-field>
                                            </div>
                                            <div v-if="item.type == 'money'">
                                                <van-field disabled
                                                        v-model="item.value"
                                                        type="number"
                                                        :name="item.title"
                                                        :label="item.title"
                                                        :placeholder="item.tips"
                                                        :required="item.required?true:false"
                                                        :rules="item.required?[{ required: item.required,pattern: /^[0-9]+(.[0-9]{2})?$/, message:'请输入正确金额'}]:[]"
                                                />
                                            </div>
                                            <div v-if="item.type == 'area'">
                                                <van-field disabled
                                                        readonly
                                                        clickable
                                                        name="area"
                                                        :value="item.value"
                                                        :label="item.title"
                                                        :placeholder="item.tips"
                                                /></van-field>
                                                <van-popup v-model="item.extra.showPicker" position="bottom">
                                                    <van-area
                                                            @confirm="setArea($event, item)"
                                                            @cancel="item.extra.showPicker = false"
                                                    />
                                                </van-popup>
                                            </div>
                                            <div v-if="item.type == 'node'">
                                                <van-field disabled
                                                        v-model="item.title"
                                                        type="textarea"
                                                        name="说明文字"
                                                        label="说明文字"/>
                                            </div>
                                                    <div v-if="item.type == 'department'">
                                                        <van-field
                                                                readonly
                                                                clickable
                                                                name="area"
                                                                :value="item.value"
                                                                :label="item.title"
                                                                :placeholder="item.tips"
                                                                @click="showDepart(item)"
                                                        />
                                                        </van-field>
                                             </div>
                                        </div>

                        </div>
                    </div>
                </div>
                <div style="text-align:center;margin-top:10px;">
                    <el-button type="primary" @click="save">保存</el-button>
                </div>
            </div>
        </div>
    </el-col>
    <el-col :span="8">
        <div class="card">
            <div class="card-head">
                设置表单项
            </div>
            <div class="card-body card-height-700">
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
                            <el-input v-model="item.extra.title2" maxlength="10" :placeholder="item.title_holder2"></el-input>
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
                            <el-input type="textarea" maxlength="500"
                                      v-model="item.title"></el-input>
                        </div>
                    </div>
                    <div class="formItemextra">
                        <div v-if="item.extra.text" v-html="item.extra.text"></div>
                        <div v-if="item.type == 'number'">
                            小数
                            <el-checkbox v-model="item.extra.floats">支持</el-checkbox>
                        </div>
                        <div v-if="item.type == 'radio' || item.type == 'checkbox'">
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
                    <div class="formItemVer" v-if="item.type != 'node'">
                        验证
                        <el-checkbox v-model="item.required" :true-label="1">必填</el-checkbox>
                    </div>
                </div>
            </div>
        </div>
    </el-col>
</el-row>
</div>
<div id="app-init-data-holder" data-school="{{ session('school.id') }}" data-newflow="{{ $flow->id }}"></div>
@endsection
