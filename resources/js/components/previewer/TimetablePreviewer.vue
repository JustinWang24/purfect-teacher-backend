<template>
    <div style="padding-bottom: 30px;">
        <h2 class="text-center mt-4">课程表预览: {{ subTitle }}</h2>
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
                user-uuid="1"
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
        data(){
            return {
                cloneFormVisible: false,
                specialCaseFormVisible: false,
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
                coursesForSpecial:[]
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
                        this.$emit('clone-action-success');
                    }
                });
            },
            createSpecialCaseColumnHandler: function (payload) {
                // 获取调课可能涉及到的课程列表
                axios.post(
                    Constants.API.LOAD_COURSES_BY_MAJOR,
                    {itemId: payload.unit.id, as: 'timetable-item-id'}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this._resetSpecialForm(); // 初始化调课表单数据
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
                console.log(this.specialCase);
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