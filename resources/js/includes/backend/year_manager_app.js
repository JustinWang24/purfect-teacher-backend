import {Util} from "../../common/utils";

if(document.getElementById('year-manager-setting-app')){
    new Vue({
        el:'#year-manager-setting-app',
        data(){
            return {
                form:{
                    school_id:null
                }
            }
        },
        created(){
            const dom = document.getElementById('app-init-data-holder');
            const mgr = JSON.parse(dom.dataset.manager);
            this.form = Util.isEmpty(mgr) ? {} : mgr;
            this.form.school_id = dom.dataset.school;
        },
        methods:{
            onSubmit: function() {
                axios.post(
                    '/school_manager/school/set-year-manager',
                    {manager: this.form}
                ).then(res =>{
                    if(Util.isAjaxResOk(res)){
                        this.$message({type:'success',message:'保存成功'});
                        window.location.reload();
                    }
                })
            },
            newManager: function () {
                this.form.id = null;
                this.form.user_id = null;
                this.form.year = null;
            }
        }
    });
}