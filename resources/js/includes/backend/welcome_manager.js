/**
 * 动态新闻的管理
 */
import {
    isShow, // 是否显示
	saveBaseInfo, // 保存迎新配置
	saveUserInfo, // 保存个人信息
    saveReportConfirmInfo, // 报到确认
    saveReportBillInfo, // 报到单
    getConfigStepListInfo, // 获取流程数据
    deleteConfigStep, // 删除流程
    upConfigStep, // 流程向上
    downConfigStep, // 流程向下
} from "../../common/welcomes";
import {Constants} from "../../common/constants";
import {Util} from "../../common/utils";

if(document.getElementById('school-welcome-list-app')){
    new Vue({
        el:'#school-welcome-list-app',
        data(){
            return {
                schoolId: null,
                is_show1:true, // 基础信息
                is_show2:false, // 个人信息
                is_show3:false, // 报到确认
                is_show4:false, // 报到单

                // 基础信息保存
                baseInfoForm:{
                    config_sdata: '', // 开始时间
                    config_edate:'', // 结束时间
                    config_content2: '' // 迎新指南
                },
                // 个人信息
                userInfoForm:{
                    photo: '', // 一寸照片
                    card_front:'', // 身份证照片
                    config_content1: '' // 注意事项
                },
                // 流程数据
                dataList2: [],
                // 预览流程
                dataList3: [],
            }
        },
        created(){
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            this.getConfigStepListInfo();
        },
        methods: {
            // 是否显示
            isShow: function (typeid) {
                if (typeid == 1) {
                    this.is_show1=true;
                    this.is_show2=false;
                    this.is_show3=false;
                    this.is_show4=false;
                }
                if (typeid == 2) {
                    this.is_show1=false;
                    this.is_show2=true;
                    this.is_show3=false;
                    this.is_show4=false;
                }
                if (typeid == 3) {
                    this.is_show1=false;
                    this.is_show2=false;
                    this.is_show3=true;
                    this.is_show4=false;
                }
                if (typeid == 4) {
                    this.is_show1=false;
                    this.is_show2=false;
                    this.is_show3=false;
                    this.is_show4=true;
                }
            },
            // 保存基础信息
            saveBaseInfoForm: function(){
                let _that_ = this;
                this.saveBaseInfoFormFlag = true;
                this.baseInfoForm.school_id = this.schoolId;
                // 保存基础配置
                saveBaseInfo(this.schoolId, this.baseInfoForm).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.$message({
                            type:'success',
                            message:res.data.message
                        });
                        _that_.getConfigStepListInfo();
                    }else{
                        this.$message.error(res.data.message);
                    }
                })
            },
            // 个人信息报错
            saveUserInfoForm: function(){
                let _that_ = this;
                this.saveUserInfoFormFlag = true;
                this.userInfoForm.school_id = this.schoolId;
                // 保存基础配置
                saveUserInfo(this.schoolId, this.userInfoForm).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.$message({
                            type:'success',
                            message:res.data.message
                        });
                        _that_.getConfigStepListInfo();
                    }else{
                        this.$message.error(res.data.message);
                    }
                })
            },
            // 报到确认
            saveReportConfirmInfo: function(){
                let _that_ = this;
                this.formData = {};
                this.formData.school_id = this.schoolId;
                // 保存基础配置
                saveReportConfirmInfo(this.schoolId, this.formData).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.$message({
                            type:'success',
                            message:res.data.message
                        });
                        _that_.getConfigStepListInfo();
                    }else{
                        this.$message.error(res.data.message);
                    }
                })
            },
            // 报到单
            saveReportBillInfo: function(){
                let _that_ = this;
                this.formData = {};
                this.formData.school_id = this.schoolId;
                // 保存基础配置
                saveReportBillInfo(this.schoolId, this.formData).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.$message({
                            type:'success',
                            message:res.data.message
                        });
                        _that_.getConfigStepListInfo();
                    }else{
                        this.$message.error(res.data.message);
                    }
                })
            },
            // 重置表单
            resetForm: function (type) {
                // 基础信息重置
                if (type == 1) {
                    this.baseInfoForm.config_sdata = '';
                    this.baseInfoForm.config_edate = '';
                    this.baseInfoForm.config_content2 = '';
                }
                // 个人信息重置
                if (type == 2) {
                    this.userInfoForm.photo = '';
                    this.userInfoForm.card_front = '';
                    this.userInfoForm.config_content1 = '';
                }
                this.getConfigStepListInfo();
            },
            // 获取流程数据列表
            getConfigStepListInfo: function () {
                let _that_ = this;

                // 获取流程信息
                this.formData = {};
                this.formData.school_id = this.schoolId;
                getConfigStepListInfo(this.schoolId, this.formData).then(res => {
                    if (Util.isAjaxResOk(res)) {
                        if (res.data.data)
                        {
                            // 初始化流程配置
                            _that_.dataList2 = res.data.data.dataList1;
                            _that_.dataList3 = res.data.data.dataList2;

                            // 初始化基础配置
                            if (!Util.isEmpty(res.data.data.baseConfigInfo)) {
                                _that_.baseInfoForm.config_sdata = res.data.data.baseConfigInfo.config_sdata;
                                _that_.baseInfoForm.config_edate = res.data.data.baseConfigInfo.config_edate;
                                _that_.baseInfoForm.config_content2 = res.data.data.baseConfigInfo.config_content2;
                            }
                            // 初始化个人配置
                            if (!Util.isEmpty(res.data.data.userConfigInfo)) {
                                _that_.userInfoForm.photo = res.data.data.userConfigInfo.steps_json_arr.photo;
                                _that_.userInfoForm.card_front = res.data.data.userConfigInfo.steps_json_arr.card_front;
                                _that_.userInfoForm.config_content1 = res.data.data.baseConfigInfo.config_content1;
                            }
                        }
                    }
                })
            },
            // 删除流程
            deleteConfigStep: function(data){
                let _that_ = this;
                this.$confirm('您确定要继续操作?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    deleteConfigStep(this.schoolId,data).then(res => {
                        if(Util.isAjaxResOk(res)){
                            this.$message({
                                type:'success',
                                message:res.data.message
                            });
                            _that_.getConfigStepListInfo();
                            _that_.resetForm(2);
                        }else{
                            this.$message.error(res.data.message);
                        }
                    })
                }).catch(() => {
                    /*this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });*/
                });
            },
            // 流程向上
            upConfigStep: function(data){
                let _that_ = this;
                upConfigStep(this.schoolId,data).then(res => {
                    if(Util.isAjaxResOk(res)){
                        _that_.getConfigStepListInfo();
                    }else{
                        this.$message.error(res.data.message);
                    }
                });
            },
            // 流程向下
            downConfigStep: function(data){
                let _that_ = this;
                downConfigStep(this.schoolId,data).then(res => {
                    if(Util.isAjaxResOk(res)){
                        _that_.getConfigStepListInfo();
                    }else{
                        this.$message.error(res.data.message);
                    }
                });
            },
        }
    })
}
