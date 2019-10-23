<template>
    <div class="courses-manager-wrap">
        <course-form school-id="1"></course-form>
    </div>
</template>

<script>
    import CourseForm from './CourseForm.vue';

    export default {
        name: "CoursesManager",
        components:{
            CourseForm
        }
    }
</script>

<style scoped>

</style>