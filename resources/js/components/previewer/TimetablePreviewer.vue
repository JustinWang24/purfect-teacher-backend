<template>
    <div style="padding-bottom: 30px;">
        <h3 class="text-center mt-4">
            <el-button v-if=" subTitle !== '' " v-on:click="switchWeekViewHandler" type="text">{{ isWeekOdd ? '目前是单周课表, 点击切换为双周课表' : '目前是双周课表, 点击切换为单周课表' }}</el-button>&nbsp;
            课程表预览: {{ subTitle }}
        </h3>
        <el-divider></el-divider>
        <div class="timetable-wrap mb-4">
            <time-slots-column :time-slots="timeSlots" class="first-column"></time-slots-column>
            <div v-for="(rows, idx) in timetable" :key="idx" class="the-column">
                <timetable-column
                        :rows="rows"
                        :weekday="idx"
                        v-on:create-new-for-current-column="createNewForCurrentColumnHandler"
                        v-on:edit-for-current-unit-column="editForCurrentUnitColumnHandler"
                        v-on:clone-for-current-unit-column="cloneForCurrentUnitColumnHandler"
                        v-on:create-special-case-column="createSpecialCaseColumnHandler"
                        v-on:show-special-cases-column="showSpecialCasesColumnHandler"
                ></timetable-column>
            </div>
        </div>
        <el-dialog title="克隆课程表项目" :visible.sync="cloneFormVisible">
            <el-form :model="cloned">
                <el-form-item label="哪一天">
                    <el-select v-model="cloned.weekday_index" style="width: 100%;">
                        <el-option :label="theWeekday"
                                   :value="(idx+1)"
                                   :key="theWeekday"
                                   v-for="(theWeekday, idx) in weekdays"></el-option>
                    </el-select>
                    <span class="help-text">说明: 指定本次安排是哪一天</span>
                </el-form-item>
                <el-form-item label="时间段">
                    <el-select v-model="cloned.time_slot_id" style="width: 100%;">
                        <el-option
                                :label="timeSlot.name"
                                :value="timeSlot.id"
                                :key="timeSlot.id"
                                v-for="timeSlot in timeSlots"
                        ></el-option>
                    </el-select>
                    <span class="help-text">说明: 指定本次安排是针对一天中的那个时段的</span>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="cancelCloneAction">取 消</el-button>
                <el-button type="primary" @click="confirmCloneAction">确 定</el-button>
            </div>
        </el-dialog>
        <el-dialog title="调课表单" :visible.sync="specialCaseFormVisible">
            <timetable-item-special-form
                :user-uuid="userUuid"
                :school-id="schoolId"
                :courses="coursesForSpecial"
                :specialTimeTableItem="specialCase"
                :to-be-replaced-item="toBeReplacedItem"
                :subtitle="subTitle"
                :special-case-cancelled="cancelSpecialCaseHandler"
                :special-case-confirmed="confirmSpecialCaseHandler"
            ></timetable-item-special-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="cancelSpecialCaseHandler">取 消</el-button>
                <el-button type="primary" @click="confirmSpecialCaseHandler">确 定</el-button>
            </div>
        </el-dialog>
        <el-dialog title="调课记录表" :visible.sync="specialsListVisible" :before-close="beforeSpecialListClose">
            <el-table :data="specials">
                <el-table-column label="日期" width="150">
                    <template slot-scope="scope">
                        <i v-if="scope.row.published" class="el-icon-check"></i>
                        <i v-else class="el-icon-video-pause"></i>
                        <i class="el-icon-time"></i>
                        <span>{{ scope.row.date }}</span>
                    </template>
                </el-table-column>
                <el-table-column property="course" label="课程" width="120"></el-table-column>
                <el-table-column property="location" label="上课地点" width="120"></el-table-column>
                <el-table-column property="teacher" label="授课教师"></el-table-column>
                <el-table-column property="updated_by" label="操作人"></el-table-column>
                <el-table-column label="操作" width="150">
                    <template slot-scope="scope">
                        <el-button
                                size="mini"
                                type="danger"
                                @click="handleSpecialCaseDelete(scope.$index, scope.row)">
                            删除
                        </el-button>
                        <el-button
                                v-if="!scope.row.published"
                                size="mini"
                                type="primary"
                                @click="handleSpecialCasePublish(scope.$index, scope.row)">
                            发布
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>
        </el-dialog>
    </div>
</template>

<script>
    import TimetableColumn from './TimetableColumn.vue';
    import TimeSlotsColumn from './TimeSlotsColumn.vue';
    import TimetableItemSpecialForm from './TimetableItemSpecialForm.vue';
    import { Constants } from '../../common/constants';
    import { Util } from '../../common/utils';

    export default {
        name: "TimetablePreviewer",
        components: {
            TimetableColumn, TimeSlotsColumn, TimetableItemSpecialForm
        },
        computed: {
            'isWeekOdd': function(){
                return this.weekType === Constants.WEEK_NUMBER_ODD;
            }
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
                required: false,
                default: '',
            },
            schoolId: {
                type: [Number, String],
                required: true
            },
            userUuid: {
                type: [Number, String],
                required: true
            },
            weekType: {    // 默认的为单周
                type: Number,
                required: false,
                default: Constants.WEEK_NUMBER_ODD
            }
        },
        data(){
            return {
                cloneFormVisible: false,
                specialCaseFormVisible: false,
                specialsListVisible: false,
                formLabelWidth: '80px',
                // 克隆表单用
                cloned: {
                    time_slot_id: '',
                    weekday_index: '',
                    from_unit_id: null,
                },
                weekdays:[],
                // 调课用
                specialCase: {
                    at_special_datetime: '',
                    to_special_datetime: '',
                    course_id: '',
                    teacher_id: '',
                    building_id: '',
                    room_id: '',
                    published: false,
                    to_replace: 0,
                },
                toBeReplacedItem: {},
                coursesForSpecial:[],
                // 显示调课的列表
                specials:[],
                anySpecialItemRemoved: false,
            }
        },
        created() {
            this.weekdays = Constants.WEEK_DAYS;
        },
        methods: {
            createNewForCurrentColumnHandler: function(payload){
                this.$emit('create-new-by-click',payload);
            },
            editForCurrentUnitColumnHandler: function (payload) {
                this.$emit('edit-unit-by-click',payload);
            },
            cloneForCurrentUnitColumnHandler: function (payload) {
                this.cloneFormVisible = true;
                this.cloned.from_unit_id = payload.unit.id;
                this.cloned.time_slot_id = payload.unit.time_slot_id;
                this.cloned.weekday_index = payload.unit.weekday_index;
            },
            cancelCloneAction: function(){
                this.cloneFormVisible = false;
                this.cloned.from_unit_id = null;
                this.cloned.time_slot_id = '';
                this.cloned.weekday_index = '';
            },
            confirmCloneAction: function () {
                // 保存克隆的项
                axios.post(
                    Constants.API.TIMETABLE.CLONE_ITEM,
                    {
                        item: {
                            id: this.cloned.from_unit_id,
                            time_slot_id: this.cloned.time_slot_id,
                            weekday_index: this.cloned.weekday_index
                        }
                    }
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.cloneFormVisible = false;
                        this.$notify({
                            title: '成功',
                            message: '课程表项已经已经克隆成功!',
                            type: 'success',
                            position: 'bottom-right'
                        });
                        this.$emit('timetable-refresh',{});
                    }
                });
            },
            showSpecialCasesColumnHandler: function(payload){
                axios.post(
                    Constants.API.TIMETABLE.LOAD_SPECIAL_CASES,
                    {ids: payload}
                ).then(res => {
                    if (Util.isAjaxResOk(res)){
                        this.specialsListVisible = true;
                        this.anySpecialItemRemoved = false;
                        this.specials = res.data.data.specials;
                    }
                });
            },
            // 创建调课记录
            createSpecialCaseColumnHandler: function (payload) {
                // 获取调课可能涉及到的课程列表
                axios.post(
                    Constants.API.LOAD_COURSES_BY_MAJOR,
                    {itemId: payload.unit.id, as: 'timetable-item-id'}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this._resetSpecialForm(payload.unit.id); // 初始化调课表单数据
                        this.toBeReplacedItem = payload.unit; // 获取到被调课的项
                        this.coursesForSpecial = res.data.data.courses;
                        this.specialCaseFormVisible = true;
                    }
                });
            },
            _resetSpecialForm: function(id){
                this.specialCase = {
                    at_special_datetime: '',
                    to_special_datetime: '',
                    course_id: '',
                    teacher_id: '',
                    building_id: '',
                    room_id: '',
                    published: false,
                    to_replace: id,
                };
            },
            cancelSpecialCaseHandler: function(){
                this.specialCaseFormVisible = false;
                this._resetSpecialForm(null); // 重置调课表单数据
                this.toBeReplacedItem = {}; // 获取到被调课的项
            },
            confirmSpecialCaseHandler: function(){
                axios.post(
                    Constants.API.TIMETABLE.CREATE_SPECIAL_CASE,
                    {specialCase: this.specialCase, user: this.userUuid}
                ).then(res=>{
                    if(Util.isAjaxResOk(res)){
                        // 创建成功, 去刷新课程表的表单
                        this.$emit('timetable-refresh',{});
                        this.$notify({
                            title: '成功',
                            message: '调课操作成功, 正为您刷新课程表 ...',
                            type: 'success',
                            position: 'bottom-right'
                        });
                        this.specialCaseFormVisible = false;
                    }else{
                        this.$notify.error({
                            title: '系统错误',
                            message: '调课操作失败, 请稍候再试 ...',
                            position: 'bottom-right'
                        });
                    }
                })
            },
            // 发布调课信息
            handleSpecialCasePublish: function(idx, row){
                this.$confirm('您将发布此调课信息, 是否确认?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    axios.post(
                        Constants.API.TIMETABLE.PUBLISH_ITEM,{id: row.id, user: this.userUuid}
                    ).then(res=>{
                        if(Util.isAjaxResOk(res)){
                            this.$notify({
                                title: '成功',
                                message: '调课信息已经发布成功',
                                type: 'success',
                                position: 'bottom-right'
                            });
                            this.specials[idx].published = true;
                        }
                    });
                }).catch((e) => {
                    console.log(e);
                    this.$notify.info({
                        title: '消息',
                        message: '发布操作已取消',
                        position: 'bottom-right'
                    });
                });
            },
            // 删除调课项
            handleSpecialCaseDelete: function(idx, row){
                this.$confirm('此操作将永久删除该调课记录, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    axios.post(
                        Constants.API.TIMETABLE.DELETE_ITEM,{id: row.id, user: this.userUuid}
                    ).then(res=>{
                        if(Util.isAjaxResOk(res)){
                            this.$notify({
                                title: '成功',
                                message: '删除成功',
                                type: 'success',
                                position: 'bottom-right'
                            });
                            this.specials.splice(idx, 1);
                            this.anySpecialItemRemoved = true;
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
            // 当调课记录 modal 关闭时: 发布事件, 让课程表刷新
            beforeSpecialListClose: function(){
                if(this.anySpecialItemRemoved){
                    // 去从新加载 preview
                    this.$emit('timetable-refresh',{})
                }
                this.specialsListVisible = false;
            },
            // 切换单双周的视图
            switchWeekViewHandler: function(){
                let weekType = Constants.WEEK_NUMBER_ODD;
                if(this.weekType === Constants.WEEK_NUMBER_ODD){
                    weekType = Constants.WEEK_NUMBER_EVEN;
                }
                this.$emit('timetable-refresh',{weekType: weekType});
            }
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