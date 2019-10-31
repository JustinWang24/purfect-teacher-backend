<template>
    <div class="courses-list-wrap">
        <el-table
                :data="courses"
                style="width: 100%">
            <el-table-column label="课程名称" width="150">
                <template slot-scope="scope">
                    <el-button type="text" v-on:click="courseNameClickedHandler">
                        <i class="el-icon-s-order"></i>
                        <span>{{ scope.row.name }}({{ scope.row.code }})</span>
                    </el-button>
                </template>
            </el-table-column>
            <el-table-column
                    label="类型"
                    width="80">
                <template slot-scope="scope">
                    <text-badge :text="scope.row.optional ? '选修' : '必修'" :color="scope.row.optional ? 'default' : 'danger'"></text-badge>
                </template>
            </el-table-column>
            <el-table-column
                    label="适用年级"
                    width="80">
                <template slot-scope="scope">
                    <el-tag size="medium" type="default">{{ yearText(scope.row.year) }}</el-tag>
                </template>
            </el-table-column>
            <el-table-column
                    label="适用学期"
                    width="80">
                <template slot-scope="scope">
                    <el-tag size="medium" type="default">{{ termText(scope.row.term) }}</el-tag>
                </template>
            </el-table-column>
            <el-table-column
                    label="学分"
                    prop="scores"
                    width="80">
            </el-table-column>
            <el-table-column
                    label="授课教师"
                    width="240">
                <template slot-scope="scope">
                    <el-tag size="medium" :key="idx" effect="plain" v-for="(t, idx) in scope.row.teachers" style="margin:2px;">{{ t.name }}</el-tag>
                </template>
            </el-table-column>
            <el-table-column
                    label="关联专业"
                    width="240">
                <template slot-scope="scope">
                    <el-tag size="medium" type="info" effect="plain" :key="idx" v-for="(m,idx) in scope.row.majors" style="margin:2px;">
                        {{ m.name }}
                    </el-tag>
                    <el-tag v-if="scope.row.majors.length === 0" size="medium" type="success" effect="plain" style="margin:2px;">
                        对所有专业都开放
                    </el-tag>
                </template>
            </el-table-column>
            <el-table-column
                    label="时间安排"
                    width="280">
                <template slot-scope="scope">
                    <el-tag size="medium" type="info" effect="plain" :key="idx" v-for="(m,idx) in scope.row.arrangements" style="margin:2px;">
                        第{{ m.week }}周的星期{{ m.day_index }}的{{ describeTimeSlot(m.time_slot_id) }}
                    </el-tag>
                    <el-tag v-if="scope.row.arrangements.length === 0" size="medium" type="success" effect="plain" style="margin:2px;">
                        整个学期
                    </el-tag>
                </template>
            </el-table-column>
            <el-table-column label="操作">
                <template slot-scope="scope">
                    <el-button-group>
                        <el-button size="mini" icon="el-icon-edit" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
                        <el-button size="mini" icon="el-icon-timer" @click="handleViewClick(scope.$index, scope.row)">课程安排</el-button>
                        <el-button v-if="canDelete" size="mini" type="danger" icon="el-icon-delete" @click="handleDelete(scope.$index, scope.row)">删除</el-button>
                    </el-button-group>
                </template>
            </el-table-column>
        </el-table>
    </div>
</template>

<script>
    import { Constants } from '../../common/constants';
    import { Util } from '../../common/utils';
    import TextBadge from '../../components/misc/TextBadge';
    export default {
        name: "CoursesList",
        components: {
            TextBadge
        },
        props: {
            courses: { // 学校的 ID
                type: Array, required: true
            },
            canDelete: { // 是否具备删除的权限
                type: Boolean, required: false, default: false
            },
            timeSlots: {
                type: Array, required: true
            }
        },
        data(){
            return {
            };
        },
        methods: {
            handleDelete: function(idx, row){
                this.$emit('course-delete', {idx: idx, course: row});
            },
            handleEdit: function(idx, row){
                this.$emit('course-edit', {idx: idx, course: row});
            },
            handleViewClick: function(idx, row){
                // 查看必修课的课程安排, 根据指定的课程 ID

            },
            yearText: function(year){
                return Constants.YEARS[year];
            },
            termText: function (term) {
                return Constants.TERMS[term];
            },
            courseNameClickedHandler: function(idx, row){
                this.$emit('course-view', {idx: idx, course: row});
            },
            describeTimeSlot: function(id){
                const slot = Util.GetItemById(id, this.timeSlots);
                if(Util.isEmpty(slot)){
                    return 'N.A';
                }
                else{
                    return slot.name;
                }
            }
        }
    }
</script>

<style scoped lang="scss">
    .courses-list-wrap{
        padding: 10px;
    }
</style>