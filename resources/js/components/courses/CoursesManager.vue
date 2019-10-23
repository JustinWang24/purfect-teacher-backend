<template>
    <div class="courses-manager-wrap">
        <div class="courses-list-col">
            <courses-list :courses="courses"></courses-list>
        </div>
        <div class="course-form-col">
            <course-form :school-id="schoolId" :course-id="courseId"></course-form>
        </div>
    </div>
</template>

<script>
    import { Constants } from '../../common/constants';
    import CourseForm from './CourseForm.vue';
    import CoursesList from './CoursesList.vue';
    export default {
        name: "CoursesManager",
        components:{
            CourseForm, CoursesList
        },
        props: {
            schoolId: {
                type: String,
                required: true
            }
        },
        data(){
            return {
                courses:[],     // 已经创建的课程列表
                courseId:null,  // 当前需要编辑的课程的 ID
            };
        },
        created(){
            this._getAllCourses();
        },
        methods: {
            _getAllCourses: function(){
                axios.post(
                    Constants.API.LOAD_COURSES_BY_SCHOOL,{school: this.schoolId}
                ).then(res => {
                    if(res.data.error_no === Constants.AJAX_SUCCESS){
                        this.courses = res.data.data.courses;
                    }
                })
            }
        }
    }
</script>

<style scoped lang="scss">
.courses-manager-wrap{
    display: flex;
    flex-direction: column;
}
</style>