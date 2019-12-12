/**
 * 校历应用
 */
import {Constants} from "../../common/constants";
import {Util} from "../../common/utils";

if(document.getElementById('school-calendar-app')){
    new Vue({
        el:'#school-calendar-app',
        data(){
            return {
                currentDate: null,
                tags: [],
                form:{
                    event_time:'',
                    tag:'',
                    content:'',
                    id:'',
                },
                events: [],
                schoolId: null,
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
            this.currentDate = moment();
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            this.events = JSON.parse(dom.dataset.events);// 校历内容
            this.tags = JSON.parse(dom.dataset.tags);// 校历内容
        },
        methods:{
            // 点击的时候, 会把点击的日期发过来, 如果是月份, 会发来第一天
            dateClicked: function(payload){
                this.currentDate = moment(payload);
                const ev = this._locateEvent(this.currentDate.format('YYYY-MM-DD'));
                if(!Util.isEmpty(ev)){
                    this.form = ev;
                }
                this.$message('正在编辑 ' + this.currentDate.format('YYYY-MM-DD') + ' 的校历');
            },
            onSubmit: function(){
                axios.post(
                    Constants.API.CALENDAR.SAVE,
                    {event: this.form}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.$message({
                            message: '校历保存成功, 正在从新加载 ...',
                            type: 'success'
                        });
                        window.location.reload();
                    }
                })
            },
            loadEvents: function(){

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
                _.each(this.events, item => {
                    if(day === item.event_time){
                        result = item;
                    }
                });
                return result;
            }
        }
    });
}