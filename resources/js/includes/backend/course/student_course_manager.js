$(document).ready(function(){
    if(document.getElementById('course-student-manager-app')){
        new Vue({
            el:'#course-student-manager-app',
            data(){
                return {
                    courseIndexerVisible: false,
                    highlight:1,
                    course:{},
                    student:{}
                }
            },
            created(){
                const dom = document.getElementById('app-init-data-holder');
                this.course = JSON.parse(dom.dataset.course);
                this.student = JSON.parse(dom.dataset.student);
            },
            methods:{
                handleMenuSelect: function(key, keyPath){
                    if(key === '1'){
                        this.courseIndexerVisible = true;
                    }
                },
                switchCourseIndex: function(payload){

                    this.courseIndexerVisible = false;
                }
            }
        });
    }
});