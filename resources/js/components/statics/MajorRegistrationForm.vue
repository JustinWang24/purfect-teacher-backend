<template>
    <div class="form-wrap">
        <el-form :model="registrationForm" :rules="rules" ref="ruleForm" label-width="100px" class="reg-form">
            <h3 style="margin-top:-10px;">个人信息</h3>
            <el-card class="box-card" :body-style="{paddingTop:'2px',paddingBottom:'2px'}">
                <el-form-item label="姓名" prop="name">
                    <el-input size="mini" v-model="registrationForm.name" placeholder="必填: 姓名"></el-input>
                </el-form-item>
                <el-form-item label="身份证号" prop="id_number">
                    <el-input size="mini" v-model="registrationForm.id_number" placeholder="必填: 身份证号"></el-input>
                </el-form-item>
                <el-form-item label="性别" prop="gender">
                    <el-select size="mini" v-model="registrationForm.gender" placeholder="必填: 性别" style="width: 100%;">
                        <el-option label="男" :value="1"></el-option>
                        <el-option label="女" :value="2"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="出生日期" required>
                    <el-date-picker
                            v-model="registrationForm.birthday"
                            align="right"
                            size="mini"
                            type="date"
                            prefix-icon="a"
                            value-format="yyyy-MM-dd"
                            placeholder="选择日期">
                    </el-date-picker>
                </el-form-item>
                <el-form-item label="民族" prop="nation_name">
                    <el-input size="mini" v-model="registrationForm.nation_name" placeholder="必填: 民族"></el-input>
                </el-form-item>
                <el-form-item label="政治面貌" prop="political_name">
                    <el-select size="mini" v-model="registrationForm.political_name" placeholder="必填: 政治面貌" style="width: 100%;">
                        <el-option label="团员" value="团员"></el-option>
                        <el-option label="党员" value="党员"></el-option>
                        <el-option label="群众" value="群众"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="生源地" prop="source_place">
                    <el-input size="mini" v-model="registrationForm.source_place" placeholder="必填: 生源地"></el-input>
                </el-form-item>
                <el-form-item label="籍贯" prop="country" class="no-border-bottom">
                    <el-input size="mini" v-model="registrationForm.country" placeholder="必填: 籍贯"></el-input>
                </el-form-item>
            </el-card>

            <h3>联系方式</h3>
            <el-card class="box-card" :body-style="{paddingTop:'2px',paddingBottom:'2px'}">
                <el-form-item label="联系电话" prop="mobile">
                    <el-input size="mini" v-model="registrationForm.mobile" placeholder="必填: 联系电话"></el-input>
                </el-form-item>
                <el-form-item label="QQ号" prop="qq">
                    <el-input size="mini" v-model="registrationForm.qq" placeholder="选填: QQ号"></el-input>
                </el-form-item>
                <el-form-item label="微信号" prop="wx">
                    <el-input size="mini" v-model="registrationForm.wx" placeholder="选填: 微信号"></el-input>
                </el-form-item>
                <el-form-item label="邮箱" prop="email" class="no-border-bottom">
                    <el-input size="mini" v-model="registrationForm.email" placeholder="必填: 邮箱"></el-input>
                </el-form-item>
            </el-card>

            <h3>家长信息</h3>
            <el-card class="box-card" :body-style="{paddingTop:'2px',paddingBottom:'2px'}">
                <el-form-item label="家长姓名" prop="parent_name">
                    <el-input size="mini" v-model="registrationForm.parent_name" placeholder="必填: 家长姓名"></el-input>
                </el-form-item>
                <el-form-item label="家长电话" prop="parent_mobile" class="no-border-bottom">
                    <el-input size="mini" v-model="registrationForm.parent_mobile" placeholder="选填: 家长电话"></el-input>
                </el-form-item>
            </el-card>

            <h3>联系地址</h3>
            <el-card class="box-card" :body-style="{paddingTop:'2px',paddingBottom:'2px'}">
                <el-form-item label="省份">
                    <el-select size="mini" v-model="registrationForm.province" placeholder="必填: 省份" style="width: 100%;">
                        <el-option v-for="(p, idx) in provinces" :key="idx" :label="p.name" :value="p.name"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="城市">
                    <el-select size="mini" v-model="registrationForm.city" placeholder="必填: 城市" style="width: 100%;">
                        <el-option v-for="(c, idx) in cities" :key="idx" :label="c.name" :value="c.name"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="城区">
                    <el-select size="mini" v-model="registrationForm.district" placeholder="必填: 城区" style="width: 100%;">
                        <el-option v-for="(d, idx) in districts" :key="idx" :label="d.name" :value="d.name"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="地址">
                    <el-input size="mini" v-model="registrationForm.address" placeholder="必填: 地址"></el-input>
                </el-form-item>
                <el-form-item label="邮政编码">
                    <el-input size="mini" v-model="registrationForm.postcode" placeholder="必填: 邮政编码"></el-input>
                </el-form-item>
            </el-card>

            <h3>申报信息</h3>
            <el-card class="box-card" :body-style="{paddingTop:'2px',paddingBottom:'2px'}">
                <el-form-item label="中/高考分数">
                    <el-input size="mini" v-model="registrationForm.examination_score" placeholder="请输入"></el-input>
                </el-form-item>
                <el-form-item label="是否服从调剂" prop="relocation_allowed" class="no-border-bottom">
                    <el-switch
                            size="mini"
                            style="float:right;margin:12px;"
                            v-model="registrationForm.relocation_allowed"
                            active-color="#13ce66"
                            inactive-color="#ff4949"
                            active-text="是"
                            inactive-text="否">
                    </el-switch>
                </el-form-item>
            </el-card>

            <p style="text-align: center;margin:40px;">
                <el-button class="showMoreButton" v-on:click="save" type="primary" round>提交申请</el-button>
            </p>
        </el-form>
        <el-scrollbar></el-scrollbar>
    </div>
</template>

<script>
    import { saveRegistrationForm } from '../../common/registration_form';
    import { provinces, cities, districts } from '../../common/location';
    import { Util } from '../../common/utils';

    export default {
        name: "MajorRegistrationForm",
        props:[
            'major','registrationForm','schoolId'
        ],
        data(){
            return {
                resetElInput:{
                    border: 'none',textAlign:'right'
                },
                rules:{
                    name: [
                        { required: true, message: '请输入您的姓名', trigger: 'blur' },
                    ],
                    id_number: [
                        { required: true, message: '请输入您的身份证号', trigger: 'blur' },
                    ],
                    source_place: [
                        { required: true, message: '请输入生源地', trigger: 'blur' },
                    ],
                    political_name: [
                        { required: true, message: '请输入您的政治面貌', trigger: 'blur' },
                    ],
                    nation_name: [
                        { required: true, message: '请输入您的民族', trigger: 'blur' },
                    ],
                    gender: [
                        { required: true, message: '请输入您的性别', trigger: 'change' }
                    ],
                    country: [
                        { required: true, message: '请输入您的籍贯', trigger: 'blur' }
                    ],
                    birthday: [
                        { type: 'date', required: true, message: '请输入您的生日', trigger: 'change' }
                    ],
                    mobile: [
                        { required: true, message: '请输入您的联系电话', trigger: 'change' }
                    ],
                    parent_name: [
                        { required: true, message: '请输入家长的联系姓名', trigger: 'blur' }
                    ],
                    parent_mobile: [
                        { required: true, message: '请输入家长的联系电话', trigger: 'blur' }
                    ]
                },
                provinces:[],
                cities:[],
                districts:[],
            }
        },
        watch: {
            'registrationForm.province': function(newVal, oldVal){
                if(newVal !== oldVal){
                    cities(newVal).then(res => {
                        this.cities = res.data.data.cities;
                    })
                }
            },
            'registrationForm.city': function(newVal, oldVal){
                if(newVal !== oldVal){
                    districts(newVal).then(res => {
                        this.districts = res.data.data.districts;
                    })
                }
            },
        },
        created() {
            provinces('/mock.json').then(res => {
                this.provinces = res.data.data.provinces;
            });
        },
        methods: {
            save: function(){
                saveRegistrationForm(this.registrationForm, this.major.id, '/mock_expect_success.json')
                    .then(res => {
                        if(Util.isAjaxResOk(res)){
                            // 保存成功
                            this.$emit('form-saved-success',{plan: this.major})
                        }else{
                            this.$emit('form-saved-failed',res.data.message)
                        }
                    });
            }
        }
    }
</script>

<style scoped lang="scss">
$defaultPadding: 15px;
$borderColor: #F6F6F6;
$txtColor: #333333;
.form-wrap{
    padding: $defaultPadding;
    margin-top: -24px;
    border-top: solid 1px $borderColor;
    padding-top: 30px;
    // 输入框外观变形
    .reg-form{
        .box-card{
            border: none;
            margin-bottom: $defaultPadding;
        }
    }

}
</style>