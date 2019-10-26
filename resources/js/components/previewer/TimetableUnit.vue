<template>
    <div v-if="isEmpty()" class="timetable-unit-wrap" :class="customCssRule()">
        <p class="text-center mt-4">
            <el-button round v-on:click="onEmptyUnitClicked">点击添加</el-button>
        </p>
    </div>
    <div v-else class="timetable-unit-wrap" :class="customCssRule()">
        <el-popover
                class="unit-content"
                placement="right"
                width="400"
                trigger="click">
            <p>
                <el-button icon="el-icon-edit" size="mini" v-if="!unit.published" v-on:click="editUnit">编辑</el-button>
                <el-button icon="el-icon-edit" size="mini" type="success" v-on:click="cloneUnit">克隆</el-button>
                <el-button icon="el-icon-share" type="primary" size="mini" v-if="unit.published" v-on:click="createSpecialCase">临时调课</el-button>
                <el-button icon="el-icon-delete" type="danger" size="mini" v-on:click="deleteUnit">删除</el-button>
            </p>
            <div class="unit-content" slot="reference">
                <p class="text-center no-margin">{{ unit.course }}</p>
                <p class="text-center no-margin">老师: {{ unit.teacher }}</p>
                <p class="text-center no-margin">地点: {{ unit.building }}</p>
                <p class="text-center no-margin">{{ unit.room }}</p>
            </div>
        </el-popover>
    </div>
</template>

<script>
    import { Util } from '../../common/utils';

    export default {
        name: "TimetableUnit",
        props: ['unit','weekday','rowIndex'],
        computed: {
            'switchStatusButtonText': function(){
                return this.unit.published;
            }
        },
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
            },
            // 空白时候点击, 相当于添加
            onEmptyUnitClicked: function(){
                this.$emit('create-new-for-current-unit',{weekday: this.weekday+1, timeSlotId: this.rowIndex});
            },
            editUnit: function(){
                this.$emit('edit-for-current-unit',{unit: this.unit});
            },
            cloneUnit: function () {
                this.$emit('clone-for-current-unit',{unit: this.unit});
            },
            createSpecialCase: function (){
                this.$emit('create-special-case',{unit: this.unit});
            },
            deleteUnit: function() {
                this.$emit('delete-unit',{unit: this.unit});
            }
        }
    }
</script>

<style scoped lang="scss">
    .timetable-unit-wrap{
        display: block;
        padding: 10px 0 10px 0;
        height: 120px;
        border: solid 1px #f1f3f7;
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