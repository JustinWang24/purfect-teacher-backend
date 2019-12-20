/**
 * 校历应用
 */

if(document.getElementById('school-attendances-list-app')){
    new Vue({
        el:'#school-attendances-list-app',
        data(){
            return {
                schoolId: null,
                attendances: [],
            }
        },
        created(){
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            this.attendances = JSON.parse(dom.dataset.attendances);// 校历内容
        }
    });
}