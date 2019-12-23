import {Util} from "../../../common/utils";

if(document.getElementById('school-teacher-management-visitors-app')){
    new Vue({
        el:'#school-teacher-management-visitors-app',
        data(){
            return {
                visitors: [],
                selectedDevice: {
                    building:{},
                    room:{}
                },
                showDetailFlag: false,
                showNewVisitorFlag: false,
                newVisitor:{
                    school_id:null,
                    name:null,
                    mobile:null,
                    vehicle_license:null,
                    reason:null,
                    scheduled_at:null,
                    arrived_at:null,
                }
            }
        },
        created(){
            this.resetNewVisitor();
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            this.visitors = JSON.parse(dom.dataset.visitors);
        },
        methods: {
            showDetail: function (row) {
                this.showDetailFlag = true;
                this.selectedDevice = row;
            },
            addVisitor: function () {
                this.showNewVisitorFlag = true;
                this.resetNewVisitor();
            },
            saveVisitor: function () {
                axios.post(
                    '/api/teacher/add-visitor',
                    {visitor: this.newVisitor}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.visitors.unshift(res.data.data.visitor);
                        this.$message({type:'success',message:'访问记录创建成功'});
                        this.showNewVisitorFlag = false;
                    }
                    else{
                        this.$message.error(res.data.message);
                    }
                }).catch(e => {
                    this.$message.error('系统繁忙: ' + e);
                });
            },
            resetNewVisitor:function () {
                this.newVisitor = {
                    school_id:this.schoolId,
                    name:null,
                    mobile:null,
                    vehicle_license:null,
                    reason:null,
                    scheduled_at:{date:null, time:null},
                    arrived_at:{date:null, time:null},
                };
            }
        }
    })
}