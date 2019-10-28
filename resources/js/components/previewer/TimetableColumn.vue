<template>
    <div class="the-column-wrap">
        <p class="header-txt">{{ weekdayText }}</p>
        <div class="the-unit-div" v-for="(unit, idx) in rows" :key="idx">
            <timetable-unit
                    :unit="unit"
                    :weekday="weekday"
                    :row-index="idx"
                    :as-manager="asManager"
                    v-on:create-new-for-current-unit="createNewForCurrentUnitHandler"
                    v-on:edit-for-current-unit="editForCurrentUnit"
                    v-on:unit-deleted="unitDeletedHandler"
                    v-on:clone-for-current-unit="unitCloneHandler"
                    v-on:create-special-case="createSpecialCaseHandler"
                    v-on:show-specials="showSpecialCasesHandler"
                    v-on:make-enquiry="makeEnquiryHandler"
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
        props: ['rows','weekday','asManager'],
        computed: {
            'weekdayText': function(){
                return Util.GetWeekdayText(this.weekday);
            }
        },
        methods: {
            createNewForCurrentUnitHandler: function(payload){
                this.$emit('create-new-for-current-column',payload);
            },
            // 编辑课程表项目
            editForCurrentUnit: function(payload){
                this.$emit('edit-for-current-unit-column',payload);
            },
            unitDeletedHandler: function (payload) {
                const idx = Util.GetItemIndexById(payload.id, this.rows);
                this.rows[idx] = '';
            },
            unitCloneHandler: function (payload) {
                this.$emit('clone-for-current-unit-column',payload);
            },
            createSpecialCaseHandler: function (payload) {
                this.$emit('create-special-case-column',payload);
            },
            showSpecialCasesHandler: function (payload) {
                this.$emit('show-special-cases-column',payload);
            },
            makeEnquiryHandler: function(payload){
                this.$emit('make-enquiry-column',payload);
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