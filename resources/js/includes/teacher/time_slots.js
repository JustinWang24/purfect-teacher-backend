/**
 * 校历应用
 */
import {Constants} from "../../common/constants";
import {Util} from "../../common/utils";

if(document.getElementById('school-time-slots-teacher-app')){
    new Vue({
        el:'#school-time-slots-teacher-app',
        data(){
            return {
                schoolId: null,
                timeFrame: [],
                highlightIdx: -1,
            }
        },
        created(){
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            this.load();
        },
        methods:{
            load: function () {
                const that = this;
                axios.post(
                    Constants.API.LOAD_TIME_SLOTS_BY_SCHOOL,{school: this.schoolId}
                ).then( res => {
                    if(Util.isAjaxResOk(res)){
                        res.data.data.time_frame.forEach(item => {
                            that.timeFrame.push({
                                timestamp: item.from + ' - ' + item.to,
                                size: this.dotSize,
                                // color: '#0bbd87',
                                type: 'primary',
                                icon: '',
                                content: item.name,
                                id: item.id,
                                origin: item
                            });
                        })
                    }
                })
            }
        }
    });
}