if(document.getElementById('school-teacher-management-students-app')){
    new Vue({
        el:'#school-teacher-management-students-app',
        data(){
            return {
                students: [],
                grades: [],
                apiToken: null,
            }
        },
        created(){
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            this.apiToken = dom.dataset.token;
            this.students = JSON.parse(dom.dataset.students);
        },
        methods: {
            showDetail: function (row) {
                window.location.href = '/h5/teacher/management/students-view?api_token=' + this.apiToken + '&grade=' + row.id;
            },
            back: function () {
                window.location.href = '/h5/teacher/management/view?api_token=' + this.apiToken + '&type=teacher';
            }
        }
    })
}