/**
 * 老师申请开设一门新的选修课
 */
import {getMajors, getTimeSlots} from "../../common/timetables";
import {Util} from "../../common/utils";
import {getEmptyElectiveCourseApplication} from "../../common/elective_course";

if(document.getElementById('teacher-apply-a-new-elective-course-app')){
    new Vue({
        el:'#teacher-apply-a-new-elective-course-app',
        data(){
            return {
                majors:[],
                timeSlots:[],
                schoolId:'',
                course:{},
            };
        },
        created(){
            this.course = getEmptyElectiveCourseApplication(true);
            this.schoolId = document.getElementById('app-init-data-holder').dataset.school;
            this.applicationId = document.getElementById('app-init-data-holder').dataset.application;
            this._getAllMajors();
            this._getAllTimeSlots();
        },
        methods: {
            _getAllTimeSlots: function(){
                getTimeSlots(this.schoolId).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.timeSlots = res.data.data.time_frame;
                    }
                })
            },
            // 获取所有可能的专业列表
            _getAllMajors: function () {
                getMajors(this.schoolId, 0).then(res=>{
                    if(Util.isAjaxResOk(res) && res.data.data.majors.length > 0){
                        this.majors = res.data.data.majors;
                    }
                    else{
                        this.$notify.error({
                            title: '错误',
                            message: '无法加载专业信息列表',
                            position: 'bottom-right'
                        });
                    }
                })
            },
        }
    });
}