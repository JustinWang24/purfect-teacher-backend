if(document.getElementById('teacher-profile-app-wrap')){
    new Vue({
        el:'#teacher-profile-app-wrap',
        data(){
            return {
                qualification:{
                    id:null,
                    title:null,
                    desc:null,
                    type:null,
                    year:null,
                    user_id:null,
                    path: null,
                    uploaded_by: null
                }
            }
        },
        created(){
            console.log(1111)
        },
        methods: {
            showForm: function (teacherId) {
                
            }
        }
    })
}