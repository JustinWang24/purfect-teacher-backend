/**
 * 迎新助手
 */
import {Util} from "../../common/utils";

if(document.getElementById('welcome-students-manager-app')){
    new Vue({
        el:'#welcome-students-manager-app',
        data(){
            return {
                schoolId: '',
                basics:[],
                showStepSelectorFlag: false,
                welcome:{
                    stepToBeAdd:null,
                },
                progress:[], // 当前的步骤
                enrolment:{},
                task:{
                    name:'',
                    describe:'',
                    type:1,
                },
            }
        },
        created(){
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            this.basics = JSON.parse(dom.dataset.basics);
        },
        methods: {
            showStepSelector: function(){
                this.showStepSelectorFlag = true;
            },
            addNewStepAction: function(){
                const item = Util.GetItemById(this.welcome.stepToBeAdd, this.basics);
                if(!Util.isEmpty(item)){
                    this.progress.push(item)
                }
            },
            resetForm: function(id){
                this.enrolment.id = null;
                this.enrolment.name = '';
                this.enrolment.enrolment_step_id = id;
                this.enrolment.school_id = this.schoolId;
                this.enrolment.campus_id = null;
                this.enrolment.describe = '';
                this.enrolment.sort = null;
                this.enrolment.user_id = null;
                this.enrolment.assists = [];
                this.enrolment.medias = [];
                this.enrolment.tasks = [];
            },
        }
    });
}