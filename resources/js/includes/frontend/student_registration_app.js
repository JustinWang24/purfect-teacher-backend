import {Constants} from "../../common/constants";
import {Util} from "../../common/utils";

if(document.getElementById('student_registration_app')){
    new Vue({
        el:'#student_registration_app',
        data(){
            return {
                majors: [],
                // hotMajors: [],
                // appliedPlans:[], // 学生已经报名的专业
                schoolId: '',
                studentMobile: '',
                studentIdNumber: '',
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
                    province:'',
                    city:'',
                    district:'',
                    address:'',
                    postcode:'',
                }
            }
        },
        created(){
            // 尝试从本地获取学生已经保存的手机号和身份证号码
            this.studentIdNumber = Util.getIdNumber();
            this.studentMobile = Util.getLocalStudentMobile();
            // 如果本地已经保存了电话或者身份证号, 那么向服务器申请学生的信息
            if(!Util.isEmpty(this.studentMobile) || !Util.isEmpty(this.studentIdNumber)){
                this.registrationForm = Util.getObjFromLocalStorage(Constants.STUDENT_PROFILE);
            }
        },
        mounted() {
            this.schoolId = document.getElementById('current-school-id').dataset.id;
            this.loadAllPlansBySchool();
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
                // 这个已经报名的, 应该被标识出来
                for(let i=0;i<this.majors.length;i++){
                    if(payload.plan.id === this.majors[i].id){
                        this.majors[i].applied = '等待审核'; // 标识这个招生信息已经被申请过了
                        break;
                    }
                }
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
            loadAllPlansBySchool: function(){
                axios.post(
                    Constants.API.RECRUITMENT.LOAD_PLANS,
                    {school: this.schoolId, version: Constants.VERSION, mobile: this.studentMobile, id_number: this.studentIdNumber}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.majors = res.data.data.plans;
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
                loadMajorDetail(major.id).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.selectedMajor = res.data.data.plan;
                        this.showAllMajorsFlag = false;
                        this.showMajorDetailFlag = true; // 显示专业详情
                    }
                    else{
                        this.$alert('无法加载专业: ' + major.name + '的详情', '加载失败', {
                            confirmButtonText: '确定',
                            type:'error',
                            customClass: 'for-mobile-alert'
                        });
                    }
                }).catch(e => {
                    console.log(e);
                    this.$alert('服务器忙, 无法加载专业: ' + major.name + '的详情. 请稍候再试!', '加载失败', {
                        confirmButtonText: '确定',
                        type:'error',
                        customClass: 'for-mobile-alert'
                    });
                })
            },
            isPlanHasBeenApplied: function(planId){
                let applied = false;
                for(let i=0;i<this.majors.length;i++){
                    if(planId === this.majors[i].id && this.majors[i].applied){
                        applied = true;
                        break;
                    }
                }
                return applied;
            }
        }
    });
}