import {Util} from "../../common/utils";

if(document.getElementById('new-attendance-app')){
    new Vue({
        el:'#new-attendance-app',
        data(){
            return {
                form:{
                    related_organizations:[]
                },
                schoolId: null,
            }
        },
        created(){
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            const att = JSON.parse(dom.dataset.attendance);
            this.form = Util.isEmpty(att) ? {} : att;
        },
        methods:{
            onSubmit: function(){
                axios.post(
                    '/school_manager/attendance/update',
                    {form: this.form}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        window.location.href = '/school_manager/attendance/list'
                    }
                });
            }
        }
    })
}