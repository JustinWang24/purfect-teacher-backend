<template>
    <div class="timetable-item-form-wrap">
        <el-steps :active="currentStep" finish-status="success" simple style="margin-bottom: 20px">
            <el-step title="时间" ></el-step>
            <el-step title="地点" ></el-step>
            <el-step title="班级" ></el-step>
            <el-step title="确认" ></el-step>
        </el-steps>
        <el-form ref="timeTableItemForm" :model="timeTableItem" label-width="80px" class="the-form">
            <div v-show="currentStep===1">
                <el-form-item label="年份">
                    <el-input v-model="timeTableItem.year"></el-input>
                </el-form-item>
                <el-form-item label="学期">
                    <el-select v-model="timeTableItem.term" style="width: 100%;">
                        <el-option :label="theTerm" :value="(idx+1)" :key="theTerm" v-for="(theTerm, idx) in terms"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="重复周期">
                    <el-select v-model="timeTableItem.repeat_unit" style="width: 100%;">
                        <el-option :label="repeatUnit"
                                   :value="(idx+1)"
                                   :key="repeatUnit"
                                   v-for="(repeatUnit, idx) in repeatUnits"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="哪一天">
                    <el-select v-model="timeTableItem.weekday_index" style="width: 100%;">
                        <el-option :label="theWeekday"
                                   :value="(idx+1)"
                                   :key="theWeekday"
                                   v-for="(theWeekday, idx) in weekdays"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="时间段">
                    <el-select v-model="timeTableItem.time_slot_id" style="width: 100%;">
                        <el-option :label="timeSlot.name" :value="timeSlot.id" :key="timeSlot.id" v-for="timeSlot in timeSlots"></el-option>
                    </el-select>
                </el-form-item>
            </div>

            <div v-show="currentStep===2">
                <el-form-item label="教学楼">
                    <el-select v-model="timeTableItem.building_id" placeholder="请选择" style="width: 100%;">
                        <el-option-group
                                v-for="item in campuses"
                                :key="item.campus"
                                :label="item.campus">
                            <el-option
                                    v-for="building in item.buildings"
                                    :key="building.id"
                                    :label="building.name"
                                    :value="building.id">
                            </el-option>
                        </el-option-group>
                    </el-select>
                </el-form-item>
                <el-form-item label="教室/地点">
                    <el-select v-model="timeTableItem.room_id" style="width: 100%;">
                        <el-option :label="room.name" :value="room.id" :key="room.id" v-for="room in rooms"></el-option>
                    </el-select>
                </el-form-item>

            </div>

            <div v-show="currentStep===3">
                <el-form-item label="专业">
                    <el-select v-model="selectedMajor" style="width: 100%;">
                        <el-option :label="major.name" :value="major.id" :key="major.id" v-for="major in majors"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="班级">
                    <el-select v-model="timeTableItem.grade_id" style="width: 100%;">
                        <el-option :label="grade.name" :value="grade.id" :key="grade.id" v-for="grade in grades"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="课程">
                    <el-select v-model="timeTableItem.course_id" style="width: 100%;">
                        <el-option :label="course.name" :value="course.id" :key="course.id" v-for="course in courses"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="授课教师">
                    <el-select v-model="timeTableItem.teacher_id" style="width: 100%;">
                        <el-option :label="teacher.name" :value="teacher.id" :key="teacher.id" v-for="teacher in teachers"></el-option>
                    </el-select>
                </el-form-item>
            </div>
            <div v-show="currentStep===4" class="summary-wrap">
                <p class="item-summary">您创建的课表详情</p>
                <el-divider></el-divider>
                <p class="item-text">
                    <span class="label-text">有效期:</span> {{ timeTableItem.year }}年{{ termText }}
                </p>
                <p class="item-text">
                    <span class="label-text">时间段:</span> {{ timeSlotText }} ({{ repeatUnitText }})
                </p>
                <el-divider></el-divider>
                <p class="item-text">
                    <span class="label-text">地点:</span> {{ locationText }}
                </p>
                <el-divider></el-divider>
                <p class="item-text">
                    <span class="label-text">上课班级:</span> {{ gradeInfoText }}
                </p>
                <p class="item-text">
                    <span class="label-text">上课内容:</span> {{ courseText }}
                </p>
                <p class="item-text">
                    <span class="label-text">授课老师:</span> {{ teacherText }}
                </p>
            </div>

            <el-form-item>
                <el-button icon="el-icon-back" type="primary" @click="goToPrev" :disabled="currentStep===1">上一步</el-button>
                <el-button icon="el-icon-right el-icon--right" type="primary" @click="goToNext" v-show="currentStep < 4">下一步</el-button>
                <el-button icon="el-icon-document-add" type="primary" @click="saveItem" v-show="currentStep === 4">我确认以上信息无误, 保存</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
    import { Constants } from '../../common/constants';
    import { Util } from '../../common/utils';

    export default {
        name: "TimetableItemForm",
        props: {
            schoolId: {
                type: String,
                required: true
            },
            userUuid: {
                type: String,
                required: true
            }
        },
        data() {
            return {
                currentStep: 1,
                selectedMajor: '',
                timeTableItem: {
                    year:'',
                    term:1,
                    repeat_unit:1,
                    weekday_index:1,
                    time_slot_id:'',
                    // 地点
                    building_id:'',
                    room_id:'',
                    grade_id:'',
                    course_id:'',
                    teacher_id:'',
                },
                timeSlots: [],
                campuses: [],
                rooms: [],
                majors: [], // 哪些专业
                grades: [], // 根据专业加载的候选班级
                courses: [], // 根据专业加载的候选班级
                teachers: [], // 根据专业加载的候选班级
                // 来自本地,暂无需远程加载的选项
                terms:[],
                repeatUnits:[],
                weekdays:[],
            }
        },
        // 监听
        watch: {
            'timeTableItem.building_id': function(newVal, oldVal){
                if(newVal !== oldVal){
                    // 去加载房间
                    this._getRoomsByBuilding(newVal);
                }
            },
            'selectedMajor': function (newVal, oldVal) {
                if(newVal !== oldVal){
                    // 去加载选定专业的班级和课程
                    this._getGradesByMajor(newVal);
                    this._getCoursesByMajor(newVal);
                    this.timeTableItem.grade_id = '';
                    this.timeTableItem.course_id = '';
                    this.timeTableItem.teacher_id = '';
                }
            },
            'timeTableItem.course_id': function(newVal, oldVal){
                if(newVal !== oldVal){
                    // 去加载房间
                    this._getTeachersByCourse(newVal);
                }
            },
            'timeTableItem.term': function(newVal, oldVal){
                // 当用户选择的学期发生变化时, 会导致专业的课程别表发生变化
                if(newVal !== oldVal){
                    // 根据专业和学期去刷新课程列表
                    this._getCoursesByMajor(newVal);
                    this.timeTableItem.course_id = ''; // 学期变了, 课程 id 值归零
                    this.timeTableItem.teacher_id = ''; // 学期变了, 授课教师 id 值归零
                }
            },
        },
        // 计算属性
        computed: {
            'termText': function(){
                if(this.timeTableItem.term)
                    return Util.GetTermText(this.timeTableItem.term);
            },
            'repeatUnitText': function () {
                if(this.timeTableItem.repeat_unit)
                    return Util.GetRepeatUnitText(this.timeTableItem.repeat_unit);
            },
            'timeSlotText': function () {
                if(this.timeTableItem.time_slot_id !== ''){
                    return Util.GetWeekdayText(this.timeTableItem.weekday_index) + ', '
                        + Util.GetItemById(this.timeTableItem.time_slot_id, this.timeSlots).name;
                }else{
                    return '';
                }
            },
            // 上课地点的表述
            'locationText': function(){
                let buildingText = '';
                if(this.timeTableItem.building_id !== '' && this.timeTableItem.room_id !== ''){
                    // 获取建筑物的文本
                    const theBuildingId = this.timeTableItem.building_id;
                    _.each(this.campuses, item => {
                        if(item.buildings){
                            const building = Util.GetItemById(theBuildingId, item.buildings);
                            if(building){
                                buildingText = item.campus + ', ' + building.name;
                            }
                        }
                    });
                    // 获取教室
                    const theRoom = Util.GetItemById(this.timeTableItem.room_id, this.rooms);
                    if(theRoom){
                        buildingText += ', ' + theRoom.name;
                    }
                }
                return buildingText;
            },
            // 上课班级的表述
            'gradeInfoText': function(){
                let result = '';
                if(this.timeTableItem.grade_id !== ''){
                    const theMajor = Util.GetItemById(parseInt(this.selectedMajor), this.majors);
                    if(theMajor){
                        result = theMajor.name + ' - ';
                    }
                    const theGrade = Util.GetItemById(this.timeTableItem.grade_id, this.grades);
                    if(theGrade){
                        result += theGrade.name;
                    }
                }
                return result;
            },
            // 上课内容的表述
            'courseText': function(){
                if(this.timeTableItem.course_id !== ''){
                    return Util.GetItemById(this.timeTableItem.course_id, this.courses).name;
                }
            },
            // 授课教师的表述
            'teacherText': function(){
                if(this.timeTableItem.teacher_id !== ''){
                    return Util.GetItemById(this.timeTableItem.teacher_id, this.teachers).name;
                }
            }
        },
        created(){
            this._getAllBuildings();
            this._getAllTimeSlots();
            this._getAllMajors();
            this.timeTableItem.year = (new Date()).getFullYear() + '';
            this.terms = Constants.TERMS;
            this.repeatUnits = Constants.REPEAT_UNITS;
            this.weekdays = Constants.WEEK_DAYS;
        },
        methods: {
            // 获取学校的所有时间段
            _getAllTimeSlots: function(){
                axios.post(
                    Constants.API.LOAD_STUDY_TIME_SLOTS_BY_SCHOOL,{school: this.schoolId}
                ).then( res => {
                    if(res.data.code === Constants.AJAX_SUCCESS){
                        this.timeSlots = res.data.data.time_frame;
                    }
                })
            },
            // 获取学校的所有建筑, 按校区分组
            _getAllBuildings: function(){
                axios.post(
                    Constants.API.LOAD_BUILDINGS_BY_SCHOOL,{school: this.schoolId}
                ).then( res => {
                    if(res.data.code === Constants.AJAX_SUCCESS){
                        this.campuses = res.data.data.campuses;
                    }
                })
            },
            // 获取某个建筑的所有房间
            _getRoomsByBuilding: function(buildingId){
                // 获取房间时, 要根据前面一个步骤选择的时间段来进行判断.
                // 如果给定年度的, 给定学期的, 给定时间段, 给定的建筑物内,
                // 某个教室是可能被占用的, 因此被占用的不可以被返回
                axios.post(
                    Constants.API.LOAD_AVAILABLE_ROOMS_BY_BUILDING,
                    {
                        school: this.schoolId,
                        building: buildingId,
                        year: this.timeTableItem.year,
                        term: this.timeTableItem.term,
                        weekday_index: this.timeTableItem.weekday_index,
                        timeSlot: this.timeTableItem.time_slot_id
                    }
                ).then( res => {
                    if(res.data.code === Constants.AJAX_SUCCESS){
                        this.rooms = res.data.data.rooms;
                    }else{
                        this.rooms = [];
                    }
                })
            },
            // 获取专业
            _getAllMajors: function(){
                axios.post(
                    Constants.API.LOAD_MAJORS_BY_SCHOOL,{id: this.schoolId}
                ).then( res => {
                    if(res.data.code === Constants.AJAX_SUCCESS){
                        this.majors = res.data.data.majors;
                    }else{
                        this.majors = [];
                    }
                })
            },
            // 根据专业获取班级
            _getGradesByMajor: function(){
                // 根据选择的专业加载班级
                if(this.selectedMajor !== ''){
                    axios.post(
                        Constants.API.LOAD_GRADES_BY_MAJOR,{id: this.selectedMajor}
                    ).then( res => {
                        if(res.data.code === Constants.AJAX_SUCCESS){
                            this.grades = res.data.data.grades;
                        }else{
                            this.grades = [];
                        }
                    })
                }
            },
            // 根据专业获取课程
            _getCoursesByMajor: function(){
                // 获取课程的时候, 除了专业, 还有学期
                if(this.selectedMajor !== '') {
                    axios.post(
                        Constants.API.LOAD_COURSES_BY_MAJOR,{id: this.selectedMajor, term: this.timeTableItem.term}
                    ).then( res => {
                        if(res.data.code === Constants.AJAX_SUCCESS){
                            this.courses = res.data.data.courses;
                        }else{
                            this.courses = [];
                        }
                    })
                }
            },
            _getTeachersByCourse: function(courseId){
                if(courseId !== ''){
                    // 传入了有效的 course id
                    axios.post(
                        Constants.API.LOAD_TEACHERS_BY_COURSE,{course: courseId}
                    ).then( res => {
                        if(res.data.code === Constants.AJAX_SUCCESS){
                            this.teachers = res.data.data.teachers;
                        }else{
                            this.teachers = [];
                        }
                    })
                }
                else{
                    this.teachers = [];
                }
            },
            goToPrev: function(){
                if(this.currentStep > 1){
                    this.currentStep--;
                }
            },
            goToNext: function () {
                if(this.currentStep < 4){
                    this.currentStep++;
                }
            },
            saveItem: function(){

            },
            // 输出文字的方法

        }
    }
</script>

<style scoped lang="scss">
.timetable-item-form-wrap{
    .the-form{
        padding-right: 10px;
        .summary-wrap{
            padding: 14px;
            .item-summary{
                font-size: 18px;
                font-weight: bold;
                color: #3490dc;
            }
            .item-text{
                font-size: 14px;
                color: #888888;
                line-height: 24px;
                .label-text{
                    color: #0c0c0c;
                    font-weight: bold;
                    width: 80px;
                    display: inline-block;
                }
            }
        }
    }
}
</style>