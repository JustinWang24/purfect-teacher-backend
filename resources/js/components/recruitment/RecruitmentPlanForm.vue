<template>
    <div class="plan-create-form">
        <el-form :model="form" :rules="rules" ref="planForm" label-width="100px" class="plan-form">
            <el-form-item label="招生标题" prop="title">
                <el-input v-model="form.title" placeholder="必填: 招生简章标题"></el-input>
            </el-form-item>

            <el-form-item label="招生计划年份">
                <el-select v-model="form.year" placeholder="请选择活动区域">
                    <el-option :label="'招生年份: ' + y + '年'" :value="y" :key="idx" v-for="(y, idx) in years"></el-option>
                </el-select>
            </el-form-item>

            <el-form-item label="招生类型">
                <el-select v-model="form.type" placeholder="请选择招生类型">
                    <el-option label="自主招生" :value="2"></el-option>
                    <el-option label="统招" :value="1"></el-option>
                </el-select>
            </el-form-item>

            <el-form-item label="专业" prop="major_id">
                <el-select v-model="form.major_id" placeholder="请选择招生专业" style="width: 100%;">
                    <el-option :label="ma.name" :value="ma.id" :key="idx" v-for="(ma, idx) in majors"></el-option>
                </el-select>
            </el-form-item>

            <el-form-item label="发布期限">
                <el-col :span="11">
                    <el-form-item prop="start_at" required>
                        <el-date-picker type="date" placeholder="选择开始日期" v-model="form.start_at" style="width: 100%;"></el-date-picker>
                    </el-form-item>
                </el-col>
                <el-col class="line" :span="2">-</el-col>
                <el-col :span="11">
                    <el-form-item>
                        <el-date-picker placeholder="选择结束日期" v-model="form.end_at" style="width: 100%;"></el-date-picker>
                    </el-form-item>
                </el-col>
            </el-form-item>

            <el-form-item label="标记为热门">
                <el-switch v-model="form.hot"></el-switch>
            </el-form-item>
            <el-form-item label="强制停止">
                <el-switch v-model="form.expired"></el-switch>
            </el-form-item>

            <el-form-item label="招生总数" prop="seats">
                <el-input v-model="form.seats" placeholder="必填: 此专业计划招生的总人数"></el-input>
            </el-form-item>
            <el-form-item label="分几个班" prop="grades_count">
                <el-input v-model="form.grades_count" placeholder="必填: 计划招几个班"></el-input>
            </el-form-item>

            <el-form-item label="学费/年" prop="fee">
                <el-input v-model="form.fee" placeholder="必填: 学费/年">
                    <template slot="append">元/年</template>
                </el-input>
            </el-form-item>

            <el-form-item label="简介" prop="tease">
                <el-input type="textarea" v-model="form.tease"  placeholder="必填: 招生专业简介"></el-input>
            </el-form-item>

            <el-form-item label="招生简章" prop="description">
                <el-input type="textarea" v-model="form.description" placeholder="必填: 招生简章"></el-input>
            </el-form-item>

            <el-form-item label="标签" prop="tags">
                <el-input type="textarea" v-model="form.tags"  placeholder="选填: 标签, 以逗号分隔"></el-input>
            </el-form-item>

            <el-form-item>
                <el-button type="primary" @click="submitForm('ruleForm')">立即创建</el-button>
                <el-button @click="resetForm('ruleForm')">重置</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
    import { getMajors } from '../../common/timetables';
    import { Constants } from '../../common/constants';
    import { Util } from '../../common/utils';

    export default {
        name: "RecruitmentPlanForm",
        props:{
            form: {
                type: Object,
                required: true
            },
            years: {
                type: Array,
                required: true,
            },
            schoolId: {
                type: [Number, String],
                required: true
            }
        },
        watch: {
            'form.major_id': function() {
                this.form.major_name = Util.GetItemById(this.form.major_id, this.majors).name;
            }
        },
        data(){
            return {
                rules:{
                    title: [
                        { required: true, message: '请输入标题', trigger: 'blur' },
                    ],
                    major_id: [
                        { required: true, message: '请选择专业', trigger: 'change' }
                    ],
                    start_at: [
                        { type: 'date', required: true, message: '请选择日期', trigger: 'blur' }
                    ],
                    seats: [
                        { required: true, message: '招生总人数不可以为空', trigger: 'blur' },
                    ],
                    fee: [
                        { required: true, message: '学费不可以为空', trigger: 'blur' },
                    ],
                    grades_count: [
                        { required: true, message: '请输入计划招几个班', trigger: 'blur' },
                    ],
                    tease: [
                        { required: true, message: '招生专业简介不可以为空', trigger: 'blur' },
                    ],
                    description: [
                        { required: true, message: '招生简章不可以为空', trigger: 'blur' },
                    ],
                },
                majors:[],
            };
        },
        created(){
            getMajors(this.schoolId, 0).then(res => {
                if(Util.isAjaxResOk(res)){
                    this.majors = res.data.data.majors;
                }
            });
        },
        methods: {
            submitForm: function(formRef){
                this.form.school_id = this.schoolId;

                const isCreateAction = Util.isEmpty(this.form.id);

                axios.post(
                    Constants.API.RECRUITMENT.SAVE_PLAN,
                    {form: this.form}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        // 保存成功
                        if(isCreateAction){
                            this.form.id = res.data.data.id;
                            this.$message({
                                message: '新的招生计划已经成功创建',
                                type: 'success'
                            });
                            this.$emit('new-plan-created'); // 发布新建计划消息
                        }else{
                            this.$emit('new-plan-updated'); // 发布更新计划消息
                            this.$message({
                                message: '招生计划'+this.form.title+'已经成功更新',
                                type: 'success'
                            });
                        }
                    }else {
                        this.$message.error('发生错误: ' + res.data.message);
                    }
                }).catch(e=>{
                    console.log(e);
                    this.$message.error('网络失去连接或服务器忙, 请稍候再试!');
                });
            },
            resetForm: function(formRef){

            }
        }
    }
</script>

<style scoped>

</style>