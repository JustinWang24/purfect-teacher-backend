<template>
    <div v-if="isEmpty(unit)" class="timetable-unit-wrap" :class="customCssRule()">
        <p class="text-center mt-4">
            <el-button v-if="asManager" round v-on:click="onEmptyUnitClicked">点击添加</el-button>
        </p>
    </div>
    <div v-else class="timetable-unit-wrap" :class="customCssRule()">
        <el-popover
                class="unit-content"
                placement="right"
                :width="asManager ? 400 : 150"
                v-model="popupVisible"
                trigger="click">
            <p>
                <el-button icon="el-icon-edit" size="mini" v-if="!unit.published && asManager" v-on:click="editUnit">编辑</el-button>
                <el-button v-if="asManager" icon="el-icon-document-copy" size="mini" type="success" v-on:click="cloneUnit">克隆</el-button>
                <el-button icon="el-icon-share" type="primary" size="mini" v-if="unit.published && asManager" v-on:click="createSpecialCase">调课</el-button>
                <el-button v-if="specialCasesCount > 0" icon="el-icon-info" type="success" size="mini" v-on:click="showSpecials">调课记录</el-button>
                <el-button v-if="asManager" icon="el-icon-delete" type="danger" size="mini" v-on:click="deleteUnit"></el-button>
                <el-button v-if="!asManager" icon="el-icon-chat-dot-round" type="primary" size="mini" v-on:click="makeEnquiry">申请表提交</el-button>
            </p>
            <div class="unit-content" slot="reference">
                <p class="text-center no-margin" style="margin-top: -10px;">
<text-badge :text="optionalText" :color="optionalColor"></text-badge>
<text-badge :text="repeatUnitText" color="primary"></text-badge>
<text-badge v-if="specialCasesCount > 0" text="调课" color="info"></text-badge>
                </p>
                <p v-if="!isEmpty(unit.course)" class="text-center no-margin">{{ unit.course }}</p>
                <p v-if="!isEmpty(unit.grade_name)" class="text-center no-margin">班级: {{ unit.grade_name }}</p>
                <p v-if="!isEmpty(unit.teacher)" class="text-center no-margin">老师: {{ unit.teacher }}</p>
                <p class="text-center no-margin">地点: {{ unit.building }}</p>
                <p class="text-center no-margin">{{ unit.room }}</p>
            </div>
        </el-popover>
    </div>
</template>

<script>
    import { Constants } from '../../common/constants';
    import { Util } from '../../common/utils';
    import TextBadge from '../misc/TextBadge';

    export default {
        name: "TimetableUnit",
        components:{
            TextBadge
        },
        props: ['unit','weekday','rowIndex','asManager'],
        computed: {
            'switchStatusButtonText': function(){
                return this.unit.published;
            },
            'optionalText': function () {
                return this.unit.optional ? '选修' : '必修';
            },
            'optionalColor': function(){
                return this.unit.optional ? 'default' : 'danger';
            },
            // 本项在未来有几节调课
            'specialCasesCount': function(){
                return Util.isEmpty(this.unit.specials) ? 0 : this.unit.specials.length;
            },
            // 周期文字
            'repeatUnitText': function(){
                return Constants.REPEAT_UNITS[this.unit.repeat_unit-1];
            }
        },
        data(){
            return {
                popupVisible: false
            };
        },
        methods: {
            isEmpty: function(some) {
                return Util.isEmpty(some);
            },
            customCssRule: function(){
                if(this.isEmpty(this.unit)){
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
                this.popupVisible = false;
                Util.pageScrollTo(0); // 移动到页面顶部
            },
            // 克隆当前的 unit, 只能改变上课的时间和地点
            cloneUnit: function () {
                this.$emit('clone-for-current-unit',{unit: this.unit});
            },
            // 创建调课的事件
            createSpecialCase: function (){
                this.$emit('create-special-case',{unit: this.unit});
            },
            // 删除当前项
            deleteUnit: function() {
                // 删除课程表项目
                this.$confirm('此操作将永久删除课程表中该项, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    axios.post(
                        Constants.API.TIMETABLE.DELETE_ITEM,{id: this.unit.id}
                    ).then(res=>{
                        if(Util.isAjaxResOk(res)){
                            this.$notify({
                                title: '成功',
                                message: '删除成功',
                                type: 'success',
                                position: 'bottom-right'
                            });
                            this.$emit('unit-deleted',{id: this.unit.id});
                        }else{
                            this.$message.error('删除失败，请联系系统管理员');
                        }
                    });
                }).catch(() => {
                    this.$notify.info({
                        title: '消息',
                        message: '删除操作已取消',
                        position: 'bottom-right'
                    });
                });
            },
            // 查看调课的记录集合
            showSpecials: function () {
                this.$emit('show-specials',this.unit.specials);
            },
            // 请假或其他事宜
            makeEnquiry: function (){
                this.$emit('make-enquiry',{
                    data: this.unit,
                    subTitle: '',
                    type: 0,
                    start_at_date: '',
                    end_at_date: '',
                    detail: '',
                    title: '',
                });
            }
        }
    }
</script>

<style scoped lang="scss">
    .timetable-unit-wrap{
        display: block;
        padding: 20px 0 10px 0;
        height: 140px;
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