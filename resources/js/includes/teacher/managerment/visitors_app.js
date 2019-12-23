import {Util} from "../../../common/utils";

if(document.getElementById('school-teacher-management-visitors-app')){
    new Vue({
        el:'#school-teacher-management-visitors-app',
        data(){
            return {
                visitors: [],
                selectedVisitor: {},
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
                },
                apiToken: null,
            }
        },
        created(){
            this.resetNewVisitor();
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            this.apiToken = dom.dataset.token;
            this.visitors = JSON.parse(dom.dataset.visitors);
        },
        methods: {
            showDetail: function (row) {
                this.showDetailFlag = true;
                this.selectedVisitor = row;
            },
            deleteVisitor: function(row){
                this.$confirm('永久删除该预约', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning',
                    center: true
                }).then(() => {
                    axios.post(
                        '/api/teacher/delete-visitor',
                        {visitor: row.id}
                    ).then(res => {
                        if(Util.isAjaxResOk(res)){
                            const idx = Util.GetItemIndexById(row.id, this.visitors);
                            this.visitors.splice(idx, 1);
                        }
                        else{
                            this.$message.error(res.data.message);
                        }
                    }).catch(e => {
                        this.$message.error('系统繁忙: ' + e);
                    });
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });
                });
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
                        res.data.data.visitor.isNew = true;
                        this.visitors.push(res.data.data.visitor);
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
            },
            handleCommand: function(d){
                window.location.href = '/h5/teacher/management/visitors-list?api_token=' + this.apiToken + '&date=' + d;
            }
        }
    })
}