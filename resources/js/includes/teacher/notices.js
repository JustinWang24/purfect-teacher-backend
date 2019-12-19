/**
 * 校历应用
 */
import {Util} from "../../common/utils";

if(document.getElementById('school-teacher-notices-list-app')){
    new Vue({
        el:'#school-teacher-notices-list-app',
        data(){
            return {
                schoolId: null,
                notices: {},
                showDetailFlag: false,
                detail: {},
            }
        },
        created(){
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            this.loadNotices();
        },
        methods:{
            loadNotices: function(){
                axios.post(
                    '/api/home/load-notices',
                    {school: this.schoolId}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.notices = res.data.data;
                    }
                });
            },
            showDetail: function (payload) {
                this.showDetailFlag = true;
                this.detail = Util.GetItemById(payload.item, this.notices.data);
            }
        }
    });
}