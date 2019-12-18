import {Util} from "../../common/utils";

if (document.getElementById('timetable-student-detail')) {
    new Vue({
        el: '#timetable-student-detail',
        data(){
            return {
                baseUrl:'',
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
                // 教师本节课程表现评分
                showTeacherEvaluateTeacherFlag: false,
                rateSummary: null,
                myRate: {
                    id: null,
                    prepare: 3,  // 准备情况
                    material: 3, // 学习材料
                    on_time: 3,   // 准时
                    positive: 3, // 生动有趣
                    result: 3,   // 授课结果是否有用
                    comment: '',   // 授课结果是否有用
                },
                rateTexts: ['极差', '失望', '一般', '满意', '惊喜'],
            }
        },
        computed:{
            'rateTitle': function () {
                return Util.isEmpty(this.myRate.id) ?
                    '请对' + this.teacher.name +'老师的本次授课进行评分'
                    : '我对' + this.teacher.name +'老师的评分';
            }
        },
        created(){
            const dom = document.getElementById('app-init-data-holder');
            this.baseUrl = dom.dataset.base;
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
            const rateSummary = JSON.parse(dom.dataset.ratesummary);
            if(!Util.isEmpty(rateSummary)){
                this.rateSummary = rateSummary;
            }

            const rate = JSON.parse(dom.dataset.rate);
            if(!Util.isEmpty(rate)){
                this.myRate = rate;
            }
        },
        methods:{

            back: function(){
                const params = {
                    api_token: this.apiToken,
                    day: this.day,
                    type: this.type,
                    odd: 1,
                    week: 15
                };
                window.location.href = Util.buildQuery(this.viewUrl, params, 'student');
            },
            showMore: function(){
                this.showMoreFlag = true;
            },
            showEvaluateTeacherForm: function () {
                this.showMoreFlag = false;
                this.showTeacherEvaluateTeacherFlag = true;
            },
            submitRate: function(){
                axios.post(
                    this.baseUrl + '/api/evaluate/student/rate-lesson',
                    {
                        api_token: this.apiToken,
                        my_rate: this.myRate,
                        timetable_item: this.item.id,
                        teacher: this.teacher.id,
                        course: this.course.id,
                    }
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.myRate.id = res.data.data.id;
                        this.$message({type:'success',message:'评价完成!'});
                        this.showTeacherEvaluateTeacherFlag = false;
                        this.showMore();
                    }else{
                        this.$message.error('系统繁忙, 请稍候再试');
                    }
                });
            }
        }
    })
}