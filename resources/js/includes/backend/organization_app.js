/**
 * 组织结构
 */
import {Constants} from "../../common/constants";
import {Util} from "../../common/utils";

if(document.getElementById('organization-app')){
    new Vue({
        el: '#organization-app',
        data(){
            return {
                maxLevel: 4,
                schoolId: 0,
                dialogFormVisible: false,
                dialogEditFormVisible: false,
                form: {
                    name: '',
                    parent_id: '',
                    level: '',
                    phone: '',
                    address: '',
                },
                parents:[],
                loading: false,
                members:[], // 当前组织的成员
                currentMember:{
                    name:'',
                    user_id:'',
                    title:'',
                    title_id:'',
                    school_id: '',
                    organization_id: '',
                    id: '',
                }
            }
        },
        watch: {
            'form.level': function(newVal, oldVal){
                if(newVal && newVal !== oldVal){
                    this.loadParents(newVal);
                }
            }
        },
        created(){
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            this.maxLevel = parseInt(dom.dataset.level);
            this.currentMember.school_id = this.schoolId;
        },
        methods: {
            editMember: function(member){
                const keys = Object.keys(member);
                keys.forEach(k => {
                    this.currentMember[k] = member[k];
                });
            },
            // 把成员加入到当前的组织中
            addToOrg: function(){
                // 检查是否选定了机构
                if(Util.isEmpty(this.form.id)){
                    this.$message.error('请指定用户所在的部门');
                }
                this.currentMember.organization_id = this.form.id;
                axios.post(
                    Constants.API.ORGANIZATION.ADD_TO_ORG,
                    {member: this.currentMember}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        const m = {};
                        const keys = Object.keys(this.currentMember);
                        keys.forEach(k => {
                            m[k] = this.currentMember[k];
                        });

                        if(Util.isEmpty(this.currentMember.id)){
                            m.id = res.data.data.id;
                            this.members.push(m);
                        }
                        else{
                            const idx = Util.GetItemIndexById(item.id, this.members);
                            if(idx > -1){
                                this.members[idx] = m;
                            }
                        }

                        this.$message({
                            type:'success',
                            message: '数据已保存'
                        });
                    }
                    else{
                        this.$message.error(res.data.message)
                    }
                })
            },
            selectMember: function(payload){
                this.currentMember.user_id = payload.item.id;
                this.currentMember.name = payload.item.value;
            },
            removeFromOrg: function(item){
                const msg = '你确认从 ' + this.form.name + ' 删除' + item.title + ': ' + item.name + '吗?';
                this.$confirm(msg, '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    axios.post(
                        Constants.API.ORGANIZATION.DELETE_FROM_ORG,
                        {id: item.id}
                    ).then(res => {
                        if(Util.isAjaxResOk(res)){
                            const idx = Util.GetItemIndexById(item.id, this.members);
                            if(idx > -1){
                                this.members.splice(idx, 1);
                            }
                            this.$message({
                                type:'success',
                                message: '删除成功'
                            });
                        }
                        else{
                            this.$message.error('操作失败, 请稍候再试.')
                        }
                    });
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });
                });
            },
            showForm: function(){
                this.dialogFormVisible = true;
            },
            loadParents: function(level){
                axios.post(
                    Constants.API.ORGANIZATION.LOAD_PARENTS,
                    {level: level}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.parents = res.data.data.parents;
                    }
                })
            },
            saveOrg: function(){
                axios.post(
                    Constants.API.ORGANIZATION.SAVE,
                    {form: this.form}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.$message({
                            message: this.form.name + '已经保存成功, 页面将重新加载',
                            type: 'success'
                        });
                        window.location.reload();
                    }
                })
            },
            // 显示组织机构编辑表格
            showEdit: function(id){
                this.loading = true;
                axios.post(
                    Constants.API.ORGANIZATION.LOAD,
                    {organization_id: id}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.form = res.data.data.organization;
                        this.members = res.data.data.members;
                    }
                    this.loading = false;
                });
                this.dialogEditFormVisible = true;
                this._resetCurrentMember();
            },
            remove: function(){
                this.$confirm('此操作将永久删除该机构以及所属的下级机构, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.loading = true;
                    axios.post(
                        Constants.API.ORGANIZATION.DELETE,
                        {organization_id: this.form.id}
                    ).then(res => {
                        if(Util.isAjaxResOk(res)){
                            this.$message({
                                type: 'success',
                                message: '删除成功!'
                            });
                            window.location.reload();
                        }
                        this.loading = false;
                    });
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });
                });
            },
            close: function(){
                this._resetForm();
                this.dialogEditFormVisible = false;
            },
            handleClose: function(done){
                this._resetForm();
                done();
            },
            _resetForm: function(){
                const keys = Object.keys(this.form);
                let that = this;
                keys.forEach(function (key) {
                    that.form[key] = '';
                });
            },
            _resetCurrentMember: function () {
                this.currentMember.name = '';
                this.currentMember.user_id = '';
                this.currentMember.title = '';
                this.currentMember.title_id = '';
                this.currentMember.organization_id = '';
                this.currentMember.id = '';
            }
        }
    });
}