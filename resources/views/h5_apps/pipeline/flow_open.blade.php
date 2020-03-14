@extends('layouts.h5_app')
@section('content')
<div id="app-init-data-holder" data-apitoken="{{ $api_token }}" data-flowid="{{ $flow->id }}" data-nodeid="{{ $node->id }}" data-school="{{ $user->getSchoolId() }}" data-nodeoptions="{{ $node->options }}" data-apprequest="1"></div>
<div id="{{ $appName }}" class="school-intro-container">
    <div class="main" style="overflow:hidden;">
        <van-form @submit="onSubmit">
            <h5 style="background:white;margin:0;padding: 10px;margin-bottom:5px;">申请人: {{ $user->name }}</h5>

    <div class="from-main">
        <div class="fromItem" v-for="item in formList">
            <div v-if="item.type == 'input' || item.type=='textarea' || item.type=='number'">
                <van-field
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
                <van-field :required="item.required?true:false" name="radio" :label="item.title">
                    <template #input>
                        <van-radio-group v-model="item.value" direction="horizontal">
                            <van-radio v-for="(item2, index2) in item.extra.itemList" :name="item2">@{{item2.itemText}}
                            </van-radio>
                        </van-radio-group>
                    </template>
                </van-field>
            </div>
            <div v-if="item.type == 'checkbox'">
                <van-field :required="item.required?true:false" name="checkboxGroup" :label="item.title">
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
                <van-field
                        :required="item.required?true:false"
                        readonly
                        clickable
                        name="datetimePicker"
                        :value="item.value"
                        :label="item.title"
                        :placeholder="item.tips"
                        @click="item.extra.showPicker = true;"
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
                    <van-field
                            :required="item.required?true:false"
                            readonly
                            clickable
                            name="datetimePicker"
                            :value="item.valueS"
                            :label="item.title"
                            :placeholder="item.tips"
                            @click="item.extra.showPickerS = true;"
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
                    <van-field
                            :required="item.required?true:false"
                            readonly
                            clickable
                            name="datetimePicker"
                            :value="item.valueE"
                            :label="item.extra.title2"
                            :placeholder="item.tips"
                            @click="item.extra.showPickerE = true;"
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
                <van-field name="uploader" :required="item.required?true:false" :label="item.title">
                    <template #input>
                        <van-uploader v-model="item.files" :max-count="9" :after-read="uploadImg" />
                    </template>
                </van-field>
            </div>
            <div v-if="item.type == 'money'">
                <van-field
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
                <van-field
                        readonly
                        clickable
                        name="area"
                        :value="item.value"
                        :label="item.title"
                        :placeholder="item.tips"
                        @click="item.extra.showPicker = true"
                /></van-field>
                <van-popup v-model="item.extra.showPicker" position="bottom">
                    <van-area
                            :area-list="provinceList"
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
        </div>
    </div>
            <h5 style="background:white;margin:0;padding: 10px;">审批流程</h5>
            <el-timeline>
                @foreach($handlers as $key => $handler)
                    <el-timeline-item key="{{ $key }}" icon="el-icon-more" type="primary" size="large">
                        @foreach($handler as $k => $val)
                            @foreach ($val as $v)
                                <el-timeline-item>
                                    {{ $v->name }}({{ $k }})
                                </el-timeline-item>
                            @endforeach
                        @endforeach
                    </el-timeline-item>
                @endforeach
            </el-timeline>

             <van-button round block type="info" native-type="submit">
                提交
             </van-button>
        </van-form>
    </div>
    @include(
        'reusable_elements.section.file_manager_component_mobile',
        ['pickFileHandler'=>'pickFileHandler']
    )
</div>
@endsection
