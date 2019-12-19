/**
 * 校历应用
 */
import {Util} from "../../common/utils";

if(document.getElementById('school-teacher-news-list-app')){
    new Vue({
        el:'#school-teacher-news-list-app',
        data(){
            return {
                schoolId: null,
                news: {},
                type: null,
                showDetailFlag: false,
                detail: {},
            }
        },
        created(){
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            this.type = dom.dataset.type;
            this.loadNews();
        },
        methods:{
            loadNews: function(){
                axios.post(
                    '/api/home/load-news',
                    {school: this.schoolId, type: this.type}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.news = res.data.data;
                    }
                });
            },
            showDetail: function (payload) {
                this.showDetailFlag = true;
                this.detail = Util.GetItemById(payload.item, this.news.data);
                console.log(this.detail);
            }
        }
    });
}