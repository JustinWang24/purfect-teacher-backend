<template>
    <div style="padding-bottom: 30px;">
        <h2 class="text-center mt-4">课程表预览: {{ subTitle }}</h2>
        <el-divider></el-divider>
        <div class="timetable-wrap mb-4">
            <time-slots-column :time-slots="timeSlots" class="first-column"></time-slots-column>
            <div v-for="(rows, idx) in timetable" :key="idx" class="the-column">
                <timetable-column :rows="rows" :weekday="idx"></timetable-column>
            </div>
        </div>
    </div>
</template>

<script>
    import TimetableColumn from './TimetableColumn.vue';
    import TimeSlotsColumn from './TimeSlotsColumn.vue';

    export default {
        name: "TimetablePreviewer",
        components: {
            TimetableColumn, TimeSlotsColumn
        },
        props: {
            timetable: {
                type: Array,
                required: true
            },
            asManager: { // 是否具备管理员的功能选项
                type: Boolean,
                required: false,
                default: false
            },
            timeSlots:{
                type: Array,
                required: true
            },
            subTitle: {
                type: String,
                required: false
            }
        },
        created() {

        },
        methods: {

        }
    }
</script>

<style scoped lang="scss">
.timetable-wrap{
    padding: 5px;
    display: block;
    .first-column, .the-column{
        width: 12.5%;
        float: left;
    }
}
</style>