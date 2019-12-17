import {Util} from "../../common/utils";
import moment from "moment";

if (document.getElementById('timetable-student-detail')) {
    new Vue({
        el: '#timetable-student-detail',
        data(){
            return {
                today:[],
                type: 'daily',
                day: null,
                appRequest: true,
                currentTimeSlotIndex: null,
                apiToken: null,
                detailUrl: null,
                viewUrl: null,
                teacherProfile: null,
                teacher: null,
                course: null,
                item: null,
                materials:[],
                showMoreFlag: false,
                showTeacherEvaluateTeacherFlag: false,
            }
        },
        created(){
            const dom = document.getElementById('app-init-data-holder');
            this.type = dom.dataset.type;
            this.day = dom.dataset.day;
            this.apiToken = dom.dataset.api;
            this.detailUrl = dom.dataset.detailurl;
            this.viewUrl = dom.dataset.viewurl;
            this.appRequest = !Util.isEmpty(dom.dataset.apprequest);
            // 课程的数据
            this.course = JSON.parse(dom.dataset.course);
            this.item = JSON.parse(dom.dataset.item);
            this.materials = JSON.parse(dom.dataset.materials);
            this.teacherProfile = JSON.parse(dom.dataset.profile);
            this.teacher = JSON.parse(dom.dataset.teacher);
        },
        methods:{
            showMore: function(slot){
                this.showMoreFlag = true;
                // const params = {
                //     api_token: this.apiToken,
                //     item: slot.lesson.id,
                //     type: this.type,
                //     day: this.day,
                //     odd: this.today.current.odd === '双周' ? 0 : 1,
                //     week: this.today.current.calendarWeekIndex
                // };
                // window.location.href = Util.buildQuery(this.detailUrl, params, 'student');
            },
            showEvaluateTeacherForm: function () {
                this.showMoreFlag = false;
                this.showTeacherEvaluateTeacherFlag = true;


            }
        }
    })
}