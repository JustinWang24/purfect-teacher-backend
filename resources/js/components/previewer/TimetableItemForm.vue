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
                        <el-option label="上学期" value="1"></el-option>
                        <el-option label="下学期" value="2"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="重复周期">
                    <el-select v-model="timeTableItem.repeat_unit" style="width: 100%;">
                        <el-option label="单周重复" value="1"></el-option>
                        <el-option label="双周重复" value="2"></el-option>
                        <el-option label="三周重复" value="3"></el-option>
                        <el-option label="四周重复" value="4"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="哪一天">
                    <el-select v-model="timeTableItem.weekday_index" style="width: 100%;">
                        <el-option label="周一" value="1"></el-option>
                        <el-option label="周二" value="2"></el-option>
                        <el-option label="周三" value="3"></el-option>
                        <el-option label="周四" value="4"></el-option>
                        <el-option label="周五" value="5"></el-option>
                        <el-option label="周六" value="6"></el-option>
                        <el-option label="周日" value="0"></el-option>
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

            <el-form-item>
                <el-button icon="el-icon-back" type="primary" @click="goToPrev" :disabled="currentStep===1">上一步</el-button>
                <el-button icon="el-icon-right el-icon--right" type="primary" @click="goToNext" v-show="currentStep < 4">下一步</el-button>
                <el-button icon="el-icon-document-add" type="primary" @click="saveItem" v-show="currentStep === 4">保存</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
    import { Constants } from '../../common/constants';

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
                    term:'1',
                    repeat_unit:'1',
                    weekday_index:'1',
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
            }
        },

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

        created(){
            this._getAllBuildings();
            this._getAllTimeSlots();
            this._getAllMajors();
            this.timeTableItem.year = (new Date()).getFullYear() + '';
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
                axios.post(
                    Constants.API.LOAD_ROOMS_BY_BUILDING,{school: this.schoolId, building: buildingId}
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

            }
        }
    }
</script>

<style scoped lang="scss">
.timetable-item-form-wrap{
    .the-form{
        padding-right: 10px;
    }
}
</style>