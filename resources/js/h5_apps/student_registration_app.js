window.axios = require('axios');
import { Constants } from '../common/constants';
import { Util } from '../common/utils';
import { queryStudentProfile, loadOpenedMajors } from '../common/registration_form';

window.Vue = require('vue');
// 引入 Element UI 库
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
Vue.use(ElementUI);

Vue.component('major-cards', require('../components/statics/MajorCards.vue').default);
Vue.component('major-registration-form', require('../components/statics/MajorRegistrationForm.vue').default);

new Vue({
    el:'#student_registration_app',
    data(){
        return {
            majors: [],
            hotMajors: [],
            appliedMajors:[], // 学生已经报名的专业
            schoolId: '',
            selectedMajor: null, // 当下被点选的专业
            // 显示所有的专业的控制
            showAllMajorsFlag: false,
            showMajorDetailFlag: false,
            showRegistrationFormFlag: false,
            showErrorMessageWindowFlag: false, // 是否显示错误信息窗口
            // 学生的报名表
            registrationForm: {
                id:'',
                name: '',
                id_number: '',
                gender: '',
                nation_name: '',
                political_name: '',
                source_place: '',
                country: '',
                mobile: '',
                birthday: '',
                qq: '',
                wx: '',
                email: '',
                parent_name: '',
                parent_mobile: '',
                examination_score: '',
                relocation_allowed: false,
            }
        }
    },
    created(){
        // 尝试从本地获取学生已经保存的手机号和身份证号码
        const id_number = Util.getIdNumber();
        const mobile = Util.getLocalStudentMobile();
        const profile = Util.getObjFromLocalStorage(Constants.STUDENT_PROFILE);
        // 如果本地已经保存了电话或者身份证号, 那么向服务器申请学生的信息
        if(!Util.isEmpty(mobile) || !Util.isEmpty(id_number)){
            queryStudentProfile(id_number, mobile).then(res => {
                if(Util.isAjaxResOk(res)){
                    Util.setObjToLocalStorage(Constants.STUDENT_PROFILE, res.data.data.profile);
                    this.appliedMajors = res.data.data.applied;
                    this.registrationForm = res.data.data.profile;
                }
            });
        }
    },
    mounted() {
        this.schoolId = document.getElementById('current-school-id').dataset.id;
        this.loadHotOpenMajorsBySchool();
        this.loadAllOpenMajorsBySchool();
    },
    methods: {
        formSavedSuccessHandler: function(payload){
            this.hideRegistrationForm();
            // 当报名成功, 把用户提供的手机号和身份证进行保存
            Util.setObjToLocalStorage(Constants.STUDENT_PROFILE,this.registrationForm);
            Util.setStudentIdNumber(this.registrationForm.id_number);
            Util.setStudentMobile(this.registrationForm.mobile);
            // 当报名成功, 显示祝贺
            const txt = '您已经成功报名专业: ' + this.selectedMajor.name + '. 我们的招生老师将审核您的报名申请, 并很快与您联系.';
            this.$alert(txt, '谢谢!', {
                confirmButtonText: '确定',
                type:'success',
                customClass: 'for-mobile-alert'
            });
        },
        formSavedFailedHandler: function(errorMsgText){
            this.hideRegistrationForm();
            // 当报名失败, 显示失败原因
            this.$alert(errorMsgText, '报名失败', {
                confirmButtonText: '确定',
                type:'error',
                customClass: 'for-mobile-alert'
            });
        },
        hideRegistrationForm: function(){
            this.showRegistrationFormFlag = false;
        },
        //
        loadHotOpenMajorsBySchool: function(){
            axios.post(
                Constants.API.LOAD_MAJORS_BY_SCHOOL,
                {id: this.schoolId, only: 'open', hot: 1}
            ).then(res => {
                if(Util.isAjaxResOk(res)){
                    this.hotMajors = res.data.data.majors;
                }
            });
        },
        loadAllOpenMajorsBySchool: function(){
            axios.post(
                Constants.API.LOAD_MAJORS_BY_SCHOOL,
                {id: this.schoolId, only: 'open'}
            ).then(res => {
                if(Util.isAjaxResOk(res)){
                    this.majors = res.data.data.majors;
                }
            });
        },
        showAllMajors: function(){
            this.showAllMajorsFlag = true;
        },
        applyMajorHandler: function (major) {
            this.showMajorDetailFlag = false;
            this.showAllMajorsFlag = false;
            this.showRegistrationFormFlag = true;
            this.selectedMajor = major;
        },
        showMajorDetailHandler: function(major){
            this.showAllMajorsFlag = false;
            this.selectedMajor = major;
            this.showMajorDetailFlag = true; // 显示专业详情
            this.selectedMajor = major;
        }
    }
});