// 学校时间段管理
import {saveTimeSlot} from "../../common/timetables";
import {Util} from "../../common/utils";

if(document.getElementById('school-time-slots-manager')){
    new Vue({
        el: '#school-time-slots-manager',
        data(){
            return {
                needReload: false,
                currentTimeSlot: {
                    id:'',
                    from:'',
                    to:'',
                    name:'',
                    type:''
                },
                showEditForm: false,
                schoolUuid:'',
            }
        },
        methods:{
            editTimeSlotHandler: function(payload){
                const keys = Object.keys(payload.timeSlot);
                keys.forEach(key => {
                    this.currentTimeSlot[key] = payload.timeSlot[key];
                });
                // this.currentTimeSlot = payload.timeSlot;
                this.schoolUuid = payload.schoolUuid;
                this.showEditForm = true;
            },
            onSubmit: function () {
                if(this.currentTimeSlot.name.trim() === ''){
                    this.$message.error('作息时间表的名称不可以为空');
                    return;
                }
                if(Util.isEmpty(this.currentTimeSlot.from)  || Util.isEmpty(this.currentTimeSlot.to)){
                    this.$message.error('作息时间表的时间段不可以为空');
                    return;
                }
                if(this.currentTimeSlot.to < this.currentTimeSlot.from){
                    this.$message.error('作息时间表的结束时间不可以早于开始时间');
                    return;
                }

                saveTimeSlot(this.schoolUuid, this.currentTimeSlot)
                    .then(res => {
                        if(Util.isAjaxResOk(res)){
                            this.$message({
                                message: '修改成功, 作息表正在重新加载 ...',
                                type: 'success'
                            });
                            window.location.reload();
                        }
                        else{
                            this.$message.error('错了哦，这是一条错误消息');
                        }
                    });
            },
            toChangedHandler: function (to) {
                if(to < this.currentTimeSlot.from){
                    this.$message.error('作息时间表的结束时间不可以早于开始时间');
                }
            }
        }
    });
}