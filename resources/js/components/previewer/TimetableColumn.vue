<template>
    <div class="the-column-wrap">
        <p class="header-txt">{{ weekdayText }}</p>
        <div class="the-unit-div" v-for="(unit, idx) in rows" :key="idx">
            <timetable-unit
                    :unit="unit"
                    :weekday="weekday"
                    :row-index="idx"
                    v-on:create-new-for-current-unit="createNewForCurrentUnitHandler"
            ></timetable-unit>
        </div>
    </div>
</template>

<script>
    import TimetableUnit from './TimetableUnit.vue';
    import { Util } from '../../common/utils';

    export default {
        name: "TimetableColumn",
        components: {
            TimetableUnit
        },
        props: ['rows','weekday'],
        computed: {
            'weekdayText': function(){
                return Util.GetWeekdayText(this.weekday);
            }
        },
        methods: {
            createNewForCurrentUnitHandler: function(payload){
                this.$emit('create-new-for-current-column',payload)
            }
        }
    }
</script>

<style scoped lang="scss">
.the-column-wrap{
    .header-txt{
        font-size: 18px;
        text-align: center;
        line-height: 40px;
        color: #0c83fc;
        font-weight: bold;
    }
}
</style>