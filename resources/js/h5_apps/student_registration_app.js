window.axios = require('axios');
import { Constants } from '../common/constants';
import { Util } from '../common/utils';

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
            schoolId: '',
            selectedMajor: null, // 当下被点选的专业
            // 显示所有的专业的控制
            showAllMajorsFlag: false,
            showMajorDetailFlag: false,
            showRegistrationFormFlag: false,
        }
    },
    mounted() {
        this.schoolId = document.getElementById('current-school-id').dataset.id;
        this.loadHotOpenMajorsBySchool();
        this.loadAllOpenMajorsBySchool();
    },
    methods: {
        loadHotOpenMajorsBySchool: function(){
            axios.post(
                Constants.API.LOAD_MAJORS_BY_SCHOOL,
                {id: this.schoolId, only: 'open'}
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
        }
    }
});