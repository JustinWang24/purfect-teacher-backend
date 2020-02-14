/**
 * 校历应用
 */
import {Constants} from "../../common/constants";
import {Util} from "../../common/utils";
import moment from 'moment';

if(document.getElementById('school-calendar-app')){
    new Vue({
        el:'#school-calendar-app',
        data(){
            return {
                currentDate: null,
                calendarDefaultDate:'',
                tags: [],
                form:{
                    event_time:'',
                    tag:'',
                    content:'',
                    id:'',
                },
                events: [],
                schoolId: null,
                specialAttendance: null,
                isLoading: false,
                showAllEvents: false,
            }
        },
        computed: {
            'showEventDeleteBtn': function(){
                return !Util.isEmpty(this.form.id);
            }
        },
        watch: {
            'currentDate': function(newDate, oldDate){
                if(!Util.isEmpty(newDate)){
                    this.form.event_time = newDate.format('YYYY-MM-DD');
                }else{
                    this.form.event_time = '';
                }
            }
        },
        created(){
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            this.events = JSON.parse(dom.dataset.events);// 校历内容
            this.tags = JSON.parse(dom.dataset.tags);// 校历内容

            let cd = dom.dataset.current;
            if(Util.isEmpty(cd)){
                cd = moment();
            }
            else{
                cd = moment(cd);
            }
            this.currentDate = cd;
            this.calendarDefaultDate = cd.format('YYYY-MM-DD');

            this.loadSpecialAttendance();
        },
        methods:{
            loadSpecialAttendance: function(){
                this.specialAttendance = null;
                this.isLoading = true;
                axios.post(
                    '/api/attendance/load-special',
                    {date: this.currentDate.format('YYYY-MM-DD'), school_id: this.schoolId}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.specialAttendance = Util.isEmpty(res.data.data.special) ? null : res.data.data.special;
                    }
                    this.isLoading = false;
                })
            },
            editSpecial: function(){
                window.location.href = '/school_manager/attendance/edit/' + this.specialAttendance.id;
            },
            // 点击的时候, 会把点击的日期发过来, 如果是月份, 会发来第一天
            dateClicked: function(payload){
                this.showAllEvents = false;
                this._resetEventForm();
                this.currentDate = moment(payload);
                const ev = this._locateEvent(this.currentDate.format('YYYY-MM-DD'));
                if(!Util.isEmpty(ev)){
                    const keys = Object.keys(ev);
                    keys.forEach(key => {
                        this.form[key] = ev[key];
                    });
                }
                this.$message('正在编辑 ' + this.currentDate.format('YYYY-MM-DD') + ' 的校历');
                this.loadSpecialAttendance();
            },
            _resetEventForm: function(){
                this.form.event_time = '';
                this.form.tag = '';
                this.form.content = '';
                this.form.id = '';
            },
            onSubmit: function(){
                if(Util.isEmpty(this.form.event_time)){
                    this.$message.error('请填写活动日期');
                    return;
                }
                if(Util.isEmpty(this.form.content)){
                    this.$message.error('请填写活动内容');
                    return;
                }

                axios.post(
                    Constants.API.CALENDAR.SAVE,
                    {event: this.form}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.$message({
                            message: '校历保存成功',
                            type: 'success'
                        });
                        this._reloadCurrentPage();
                    }
                })
            },
            // 删除事件
            deleteEvent: function(event){
                let eventId = this.form.id;
                let eventTime = this.form.event_time;
                if(!Util.isEmpty(event)){
                    eventId = event.id;
                    eventTime = event.event_time;
                }

                this.$confirm('此操作将永久删除在 '+eventTime+'日所安排的事件, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {

                    axios.post(
                        Constants.API.CALENDAR.DELETE,
                        {event_id: eventId}
                    ).then(res => {
                        if(Util.isAjaxResOk(res)){
                            this.$message({
                                message: '已经成功删除, 正在从新加载 ...',
                                type: 'success'
                            });
                            this._reloadCurrentPage();
                        }
                    })
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });
                });
            },
            _reloadCurrentPage: function(){
                window.location.href = '/school_manager/calendar/index?cd=' + this.currentDate.format('YYYY-MM-DD');
            },
            /**
             *
             * @param day: YYYY-MM-DD
             * @returns {*}
             */
            getEvent: function(day){
                return this._locateEvent(day);
            },
            _locateEvent: function(day){
                let result = null;
                this.events.forEach(item => {
                    if(day === item.event_time){
                        result = item;
                    }
                });
                return result;
            }
        }
    });
}