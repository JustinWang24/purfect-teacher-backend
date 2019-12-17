import {Util} from "../../common/utils";
import moment from "moment";

if (document.getElementById('timetable-student-view')) {
    new Vue({
        el: '#timetable-student-view',
        data(){
            return {
                today:[],
                type: 'daily',
                day: null,
                appRequest: true,
                list:[],
                currentTimeSlotIndex: null,
                apiToken: null,
                detailUrl: null,
                viewUrl: null,
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
            this.today = JSON.parse(dom.dataset.today);
            const that = this;
            this.today.timetable[0].forEach(function(lesson, idx) {
                let obj = {};
                obj.from = that.today.timeSlots[idx].from;
                obj.to = that.today.timeSlots[idx].to;
                obj.name = that.today.timeSlots[idx].name;
                obj.current = that.today.timeSlots[idx].current;
                obj.id = that.today.timeSlots[idx].id;
                if(obj.current){
                    that.currentTimeSlotIndex = idx;
                }
                obj.lesson = lesson;
                that.list.push(obj);
            });
        },
        methods:{
            enter: function(slot){
                const params = {
                    api_token: this.apiToken,
                    item: slot.lesson.id,
                    type: this.type,
                    day: this.day,
                    odd: this.today.current.odd === '双周' ? 0 : 1,
                    week: this.today.current.calendarWeekIndex
                };
                window.location.href = Util.buildQuery(this.detailUrl, params, 'student');
            },
            move: function(prev){
                let date = moment(this.day);
                const params = {
                    api_token: this.apiToken,
                    type: this.type,
                    day: prev ? date.subtract(1,'d').format("YYYY-MM-DD") : date.add(1,'d').format("YYYY-MM-DD"),
                    odd: this.today.current.odd === '双周' ? 0 : 1,
                    week: this.today.current.calendarWeekIndex
                };
                window.location.href = Util.buildQuery(this.viewUrl, params, 'student');
            }
        }
    })
}