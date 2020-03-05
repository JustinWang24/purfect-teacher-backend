import {Util} from "../../common/utils";
import {Constants} from "../../common/constants";

if (document.getElementById('pipeline-flows-manager_form-app')) {
    new Vue({
        el: '#pipeline-flows-manager_form-app',
        data(){
            return {
                schoolId: null,
                formList: [
                    /*                  formList列表属性定义
                     {
                     // 表单组件名
                     name: '单行输入框',
                     // 表单组件类型
                     type: 'input',
                     // 标题
                     title: '',
                     // 提示文字
                     tips: '',
                     // 组件详细信息
                     extra: {
                     // 组件说明
                     text: '用户申请时填写的内容最多可填写100个字',
                     // 数字输入框是否接受浮点数
                     floats: true,
                     // 单多选框选项列表
                     itemList: [
                     {
                     // 单多选框选项名称
                     itemText: '选项1',
                     },
                     {
                     itemText: '选项2',
                     },
                     {
                     itemText: '选项3',
                     }
                     ],
                     // 日期选择框日期格式 1:yyyy-mm-dd hh:mm 2:yyyy-mm-dd;
                     dateType: 1,
                     // 部门选择能否多选 1: 不可多选 2:可多选
                     depType: 1,
                     },
                     // 是否必填
                     required: false
                     },*/
                ],
                itemMap: [
                    {
                        name: '单行输入框',
                        type: 'input',
                        value: '',
                        title: '备注说明',
                        title_holder: '备注说明',
                        tips: '请输入',
                        tips_holder: '请输入',
                        extra: {
                            text: '用户申请时填写的内容最多可填写100个字'
                        },
                        required: false,
                        icon: 'el-icon-document-remove'
                    },
                    {
                        name: '多行输入框',
                        type: 'textarea',
                        value: '',
                        title: '标题',
                        tips: '提示',
                        extra: {
                            text: '用户申请时填写的内容最多可填写200个字'
                        },
                        required: false,
                        icon: 'el-icon-document'
                    },
                    {
                        name: '数字输入框',
                        type: 'number',
                        value: '',
                        title: '数量',
                        title_holder: '数量',
                        tips: '请输入数量',
                        tips_holder: '请输入数量',
                        extra: {
                            text: '',
                            floats: true
                        },
                        required: false,
                        icon: 'el-icon-document-remove'
                    },
                    {
                        name: '单选框',
                        type: 'radio',
                        value: '',
                        title: '填报哪个',
                        title_holder: '填报哪个',
                        tips: '请选择',
                        tips_holder: '请选择',
                        extra: {
                            text: '',
                            itemList: [
                                {
                                    itemText: '选项1',
                                },
                                {
                                    itemText: '选项2',
                                },
                                {
                                    itemText: '选项3',
                                }
                            ]
                        },
                        required: false,
                        icon: 'el-icon-circle-check'
                    },
                    {
                        name: '多选框',
                        type: 'checkBox',
                        value: '',
                        title: '申请单位',
                        title_holder: '申请单位',
                        tips: '请选择(可多选)',
                        tips_holder: '请选择(可多选)',
                        extra: {
                            text: '',
                            itemList: [
                                {
                                    itemText: '选项1',
                                },
                                {
                                    itemText: '选项2',
                                },
                                {
                                    itemText: '选项3',
                                }
                            ]
                        },
                        required: false,
                        icon: 'el-icon-circle-check'
                    },
                    {
                        name: '日期',
                        type: 'date',
                        value: '',
                        title: '报销日期',
                        title_holder: '报销日期',
                        tips: '请选择',
                        tips_holder: '请选择',
                        extra: {
                            text: '',
                            dateType: 1
                        },
                        required: false,
                        icon: 'el-icon-date'
                    },
                    {
                        name: '日期区间',
                        type: 'date-date',
                        value: '',
                        title: '开始时间',
                        title_holder: '开始时间',
                        title2: '结束时间',
                        title_holder2: '结束时间',
                        tips: '',
                        tips_holder: '请选择',
                        extra: {
                            text: '',
                            dateType: 2
                        },
                        required: false,
                        icon: 'el-icon-date'
                    },
                    {
                        name: '图片',
                        type: 'image',
                        value: '',
                        title: '图片',
                        title_holder: '图片',
                        extra: {
                            text: '图片最多可添加9张',
                        },
                        required: false,
                        icon: 'el-icon-picture-outline'
                    },
                    {
                        name: '金额',
                        type: 'money',
                        value: '',
                        title: '金额(元)',
                        title_holder: '金额(元)',
                        tips: '请输入金额',
                        tips_holder: '请输入金额',
                        extra: {
                            text: '',
                        },
                        required: false,
                        icon: 'el-icon-coin'
                    },
                    {
                        name: '附件',
                        type: 'files',
                        value: '',
                        title: '上传附件',
                        title_holder: '上传附件',
                        tips: '',
                        tips_holder: '',
                        extra: {
                            text: '',
                        },
                        required: false,
                        icon: 'el-icon-paperclip'
                    },
                    {
                        name: '关联部门',
                        type: 'department',
                        value: '',
                        title: '关联部门',
                        title_holder: '',
                        tips: '请选择',
                        tips_holder: '',
                        extra: {
                            text: '',
                            depType: 1
                        },
                        required: false,
                        icon: 'el-icon-house'
                    },
                    {
                        name: '地点',
                        type: 'area',
                        value: '',
                        title: '出差地点',
                        title_holder: '出差地点',
                        tips: '请选择',
                        tips_holder: '请选择',
                        extra: {
                            text: '',
                        },
                        required: false,
                        icon: 'el-icon-location-outline'
                    },
                    {
                        name: '说明文字',
                        type: 'node',
                        value: '',
                        title: '说明文字',
                        nodeText: '',
                        extra: {
                            text: '',
                        },
                        required: false,
                        icon: 'el-icon-warning-outline'
                    }
                ],
                rules:{
                    ipnut: [{ required: true, message: '请输入', trigger: 'blur' },]
                },
                radio:""
            }
        },
        created(){
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            // 载入时间选择
            console.log('表单');
        },
        methods: {
            clickItem(item) {
                let newItem = JSON.parse(JSON.stringify(item));
                this.formList.push(newItem)
            },
            removeItem(index) {
                this.formList.splice(index, 1)
            },
            addRadioItem (item, index) {
                let items = {itemText: '选项',};
                console.log(item)
                item.splice(index + 1, 0, items)
                console.log(this.formList)
            },
            removeRadioItem (item, index) {
                item.splice(index, 1)
                console.log(this.formList)
            },
            textareaInput(value) {

            }
        }
    });
}
