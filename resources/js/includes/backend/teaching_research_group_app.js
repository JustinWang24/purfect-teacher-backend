// 快速定位用户的搜索框: 会更加当前的状况, 来搜索用户和学院 系等
import {Util} from "../../common/utils";

if(document.getElementById('teaching-research-app')){
    new Vue({
        el:'#teaching-research-app',
        data(){
            return{
                group:{
                    type:'',
                    user_id:'',
                    user_name:'',
                    name:'',
                    school_id:'',
                },
                members:[],
            }
        },
        created(){
            const dom = document.getElementById('app-init-data-holder');
            this.group.school_id = dom.dataset.school;
            const members = JSON.parse(dom.dataset.members);
            const group = JSON.parse(dom.dataset.group);
            if(!Util.isEmpty(group)){
                this.group = group;
            }
            if(!Util.isEmpty(members)){
                this.members = members;
            }
        },
        methods: {
            onSubmit: function(){
                axios.post(
                    '/school_manager/organizations/teaching-and-research-group/save',
                    {group: this.group}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.$message({type:'success',message:'保存成功'});
                        window.location.href='/school_manager/organizations/teaching-and-research-group';
                    }
                    else{
                        this.$message.error(res.data.message);
                    }
                });
            },
            onUserSelected: function (payload) {
                this.group.user_id = payload.item.id;
                this.group.user_name = payload.item.value;
            },
            onMemberSelected: function (payload) {
                const member = {
                    user_id: payload.item.id,
                    user_name: payload.item.value,
                    group_id: this.group.id
                };
                this.members.push(member);

            },
            deleteMember: function (idx) {
                const theMember = this.members[idx];
                if(Util.isEmpty(theMember.id)){
                    this.members.splice(idx, 1);
                    return;
                }

                this.$confirm('此操作将永久删除成员: '+theMember.user_name+', 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    axios.post(
                        '/school_manager/organizations/teaching-and-research-group/delete-member',
                        {member_id: theMember.id}
                    ).then(res => {
                        if(Util.isAjaxResOk(res)){
                            this.$message({type:'success',message:'删除成功'});
                            this.members.splice(idx, 1);
                        }
                        else{
                            this.$message.error(res.data.message);
                        }
                    });
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });
                });

            },
            onMembersSubmit: function(){
                axios.post(
                    '/school_manager/organizations/teaching-and-research-group/save-members',
                    {members: this.members}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.$message({type:'success',message:'保存成功'});
                        window.location.href='/school_manager/organizations/teaching-and-research-group';
                    }
                    else{
                        this.$message.error(res.data.message);
                    }
                });
            }
        }
    })
}