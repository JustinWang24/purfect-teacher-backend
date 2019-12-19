/**
 * 校历应用
 */

if(document.getElementById('school-teacher-news-list-app')){
    new Vue({
        el:'#school-teacher-news-list-app',
        data(){
            return {
                schoolId: null,
                news: [],
                type: null,
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

            }
        }
    });
}