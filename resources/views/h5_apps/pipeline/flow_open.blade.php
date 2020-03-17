@extends('layouts.h5_app')
@section('content')
<div id="app-init-data-holder" data-apitoken="{{ $api_token }}" data-flowid="{{ $flow->id }}" data-nodeid="{{ $node->id }}" data-school="{{ $user->getSchoolId() }}" data-nodeoptions="{{ $node->options }}" data-apprequest="1"></div>
<div id="{{ $appName }}" class="school-intro-container">
    <div class="main" style="overflow:hidden;">
        <van-form @submit="onSubmit">
           <!-- <h5 style="background:white;margin:0;padding: 10px;margin-bottom:5px;">申请人: {{ $user->name }}</h5> -->

    <div class="from-main">
        <div class="fromItem" v-for="item in formList">
            <div v-if="item.type == 'input' || item.type=='textarea' || item.type=='number'">
                <van-field
                        v-model="item.value"
                        :type="item.type"
                        :name="item.title"
                        :label="item.title"
                        :placeholder="item.tips"
                        :maxlength="item.type == 'input' || item.type=='number'?20:200"
                        show-word-limit
                        :required="item.required?true:false"
                        :rules="item.required?[{ required: item.required,pattern: item.type=='number'?(item.extra.floats?/^[0-9]+(.[0-9]{2})?$/:/^(\-|\+)?\d*$/):'', message:item.type=='number'?(item.extra.floats?'支持输入两位小数':'请输入整数'): '请填写'+item.tips }]:[]"
                />
            </div>
            <div class="myRadio" v-if="item.type == 'radio'">
                <div class="myTips" v-html="item.tips"></div>
                <van-field :required="item.required?true:false" name="radio" :label="item.title">
                    <template #input>
                        <van-radio-group v-model="item.value">
                            <van-radio v-for="(item2, index2) in item.extra.itemList" :name="item2">@{{item2.itemText}}
                            </van-radio>
                        </van-radio-group>
                    </template>
                </van-field>
            </div>
            <div class="myRadio" v-if="item.type == 'checkbox'">
                <div class="myTips" v-html="item.tips"></div>
                <van-field :required="item.required?true:false" name="checkboxGroup" :label="item.title">
                    <template #input>
                        <van-checkbox-group v-model="item.value">
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
                        :placeholder="item.tips + '  >'"
                        @click="showPicker(item)"
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
                            :placeholder="item.tips + '  >'"
                            @click="showPickerStart(item)"
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
                            :placeholder="item.tips + '  >'"
                            @click="showPickerEnd(item)"
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
            <div class="myRadio" v-if="item.type == 'image'">
                <div class="myTips" v-html="item.tips"></div>
                <van-field name="uploader" :required="item.required?true:false" :label="item.title">
                    <template #input>
                        <van-uploader v-model="item.files" :max-count="9" :after-read="uploadImg"/>
                    </template>
                </van-field>
            </div>
            <div class="myRadio" v-if="item.type == 'files'">
                <div class="myTips" v-html="item.tips"></div>
                <van-field name="uploader" :required="item.required?true:false" :label="item.title">
                    <template #input>
                        <van-uploader v-model="item.files" :max-count="9" :after-read="uploadImg">
                            <van-button size="small" type="info">上传文件</van-button>
                        </van-uploader>
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
                        :required="item.required?true:false"
                        :value="item.value"
                        :label="item.title"
                        :placeholder="item.tips + '  >'"
                        @click="item.extra.showPicker = true"
                />
                </van-field>
                <van-popup v-model="item.extra.showPicker" position="bottom">
                    <van-area
                            :area-list="provinceList"
                            @confirm="setArea($event, item)"
                            @cancel="item.extra.showPicker = false"
                    />
                </van-popup>
            </div>
            <div class="myRadio myNode" v-if="item.type == 'node'">
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
                        :required="item.required?true:false"
                        :value="item.value"
                        :label="item.title"
                        :placeholder="item.tips + '  >'"
                        @click="showDepart(item)"
                />
                </van-field>
                <van-action-sheet
                        v-model="item.extra.showPicker" :title="item.title">
                    <div class="part-content">
                        <div v-show="!item.partEnter" class="partGroup clearfix" v-for="itemParent in part">
                            <div v-for="item2 in itemParent.organ" class="partItem"
                                 :class="{'part-active': item2.active}" @click="clickPart(item2, itemParent, item.extra.depType)"
                                 v-html="item2.name"></div>
                        </div>
                        <div v-show="item.partEnter">
                            <div class="partListItem" v-for="partItem in selectParts" v-html="partItem.name">

                            </div>
                        </div>
                    </div>
                </van-action-sheet>
                <button v-show="item.extra.showPicker" type="button" class="van-action-sheet__cancel" @click="partNext(item)">@{{item.cancelText}}</button>
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

                   @foreach($handlers as $key => $handler)
                   <!-- <el-timeline-item key="{{ $key }}" icon="审批状态"  :timestamp="时间戳2018-04-12 20:46">-->
                       <el-timeline-item key="{{ $key+1 }}">
                           @foreach($handler as $k => $val)
                               @foreach ($val as $v)
                                   <el-timeline-item @if (!empty($v->result)) result="{{ $v->result->result }}" @if($v->result->result != \App\Utils\Pipeline\IAction::RESULT_PENDING) timestamp="{{ substr($v->result->updated_at, 0, 16) }}" @endif @endif>
                                       <img src="{{ $v->profile->avatar }}" alt="" style="width: 40px; height: 40px;border-radius: 50%;vertical-align: middle;">
                                       {{ $v->name }}({{ $k }})
                                   </el-timeline-item>
                               @endforeach
                           @endforeach
                       </el-timeline-item>
                   @endforeach

           </el-timeline>
          <div style="width: 100%;background: white;text-align: center">
                    <van-button style="width: 70%;margin: 0 auto" round block type="info" native-type="submit">
                         提交
                    </van-button>
          </div>
          </van-form>
           </div>
    @include(
        'reusable_elements.section.file_manager_component_mobile',
        ['pickFileHandler'=>'pickFileHandler']
    )
</div>
@endsection
