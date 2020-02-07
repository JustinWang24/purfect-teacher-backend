// 后台的招生计划的管理程序
import {Constants} from "../../common/constants";
import {Util} from "../../common/utils";

if(document.getElementById('school-recruitment-manager-app')){
    new Vue({
        el:'#school-recruitment-manager-app',
        data(){
            return {
                form:{},
                year:'',
                years:[],
                plans:[],
                reloading: false,
                pageSize:20,
                schoolId: null,
                userUuid: null,
                flag: true,
                // 控制表单是否显示
                showRecruitmentPlanFormFlag: false,
            }
        },
        created(){
            const el = document.getElementById('plan-manager-school-id');
            this.schoolId = el.dataset.id;
            this.userUuid = el.dataset.uuid;
            const theDate = new Date();
            const month = theDate.getMonth();
            if(month < 8){
                this.year = theDate.getFullYear();
            }
            else{
                this.year = theDate.getFullYear() + 1;
            }

            this.years.push(this.year + 1);
            this.years.push(this.year);
            this.years.push(this.year - 1);

            this._resetFormData();
        },
        watch:{
            'year': function(newVal) {
                this.loadPlans(0);
            }
        },
        methods: {
            // 创建新招生计划
            createNewPlan: function(){
                this._resetFormData();
                this.flag = !this.flag;
                this.showRecruitmentPlanFormFlag = true;
            },
            onEditPlanHandler: function(payload){
                this.showRecruitmentPlanFormFlag = true;
                this.form = Util.GetItemById(payload.plan.id, this.plans);
                this.$message('您正在编辑招生计划: ' + this.form.title);
                this.flag = !this.flag;
            },
            // 当删除按钮被点击
            onDeletePlanHandler: function(payload){
                axios.post(
                    Constants.API.RECRUITMENT.DELETE_PLAN,
                    {version: Constants.VERSION, plan: payload.plan.id, user: this.userUuid }
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        const idx = Util.GetItemIndexById(payload.plan.id, this.plans);
                        if(idx > -1){
                            this.plans.splice(idx, 1);
                            if(payload.plan.id === this.form.id){
                                // 正好删除的是正在被编辑的计划, 那么重置编辑表单
                                this._resetFormData();
                                this.flag = !this.flag;
                            }
                            this.$message({
                                message: '招生计划: ' + payload.plan.title + '已被删除',
                                type: 'success'
                            });
                        }
                    }
                    else{
                        this.$message.error('无法删除招生计划');
                    }
                })
            },
            // 当新计划被成功创建
            newPlanCreatedHandler: function(payload){
                this.showRecruitmentPlanFormFlag = false;
                this.loadPlans(0);
            },
            planUpdatedHandler: function(payload){
                this.showRecruitmentPlanFormFlag = false;
                this.loadPlans(0);
            },
            loadPlans: function(pageNumber){
                this.plans = [];
                axios.post(
                    Constants.API.RECRUITMENT.LOAD_PLANS,
                    {
                        school: this.schoolId,
                        pageNumber: pageNumber,
                        pageSize: this.pageSize,
                        year: this.year,
                        uuid: this.userUuid,// 用户 uuid, 用来加载特定的招聘计划
                        version: Constants.VERSION
                    }
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.plans = res.data.data.plans;
                        if(this.plans.length > 0){
                            this.form = this.plans[0];
                        }
                    }
                });
            },
            _resetFormData: function(){
                this.form = {
                    id:'',
                    major_id:'',
                    major_name:'',
                    type:'',
                    title:'',
                    start_at:'',
                    end_at:'',
                    description:'',
                    tease:'',
                    tags:'',
                    fee:'',
                    hot:false,
                    seats:'',
                    grades_count:'',
                    year:this.year,
                    manager_id: '',
                    enrol_manager: '',
                    target_students: '',
                    student_requirements: '',
                    how_to_enrol: '',
                    manager_name: '',
                    enrol_manager_name: '',
                    opening_date: '',
                };
            }
        }
    });
}