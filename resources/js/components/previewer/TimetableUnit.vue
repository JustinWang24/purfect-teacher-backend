<template>
    <div class="timetable-unit-wrap" :class="customCssRule()">
        <p v-if="isEmpty()" class="text-center mt-2">未安排</p>
        <div v-else class="unit-content">
            <p class="text-center no-margin">{{ unit.course }}</p>
            <p class="text-center no-margin">老师: {{ unit.teacher }}</p>
            <p class="text-center no-margin">地点: {{ unit.building }}</p>
            <p class="text-center no-margin">{{ unit.room }}</p>
        </div>
    </div>
</template>

<script>
    import { Util } from '../../common/utils';

    export default {
        name: "TimetableUnit",
        props: ['unit'],
        methods: {
            isEmpty: function() {
                return Util.isEmpty(this.unit);
            },
            customCssRule: function(){
                if(this.isEmpty()){
                    return '';
                }
                else if(this.unit.published){
                    return 'confirmed';
                }
                else{
                    return 'draft';
                }
            }
        }
    }
</script>

<style scoped lang="scss">
    .timetable-unit-wrap{
        display: block;
        padding: 10px 0 10px 0;
        height: 120px;
        border: solid 1px #DCDFE6;
        background-color: #F5F7FA;
        color: #888888;
    }
    .draft{
        background-color: #E6A23C;
        color: white;
    }
    .confirmed{
        background-color: #67C23A;
        color: white;
    }
</style>