<template>
    <div class="elective-course-form-wrap">
        <p v-if="loading" style="color: red;"><i class="el-icon-loading"></i> 数据加载中, 请稍候 ...</p>
        <el-form :model="courseModel" :rules="rules" ref="electivCourseModelForm" label-width="100px" class="course-form">
            <el-row>
                <el-col :span="6">
                    <el-form-item label="课程编号" prop="code">
                        <el-input v-model="courseModel.code" placeholder="必填: 课程编号, 请注意保证课程编号的唯一性"></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="6">
                    <el-form-item label="课程名称" prop="name">
                        <el-input v-model="courseModel.name" placeholder="必填: 课程名称"></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="6">
                    <el-form-item label="学分" prop="scores">
                        <el-input v-model="courseModel.scores" placeholder="选填: 课程学分"></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="6">
                    <el-form-item label="开课年份">
                        <el-select v-model="courseModel.start_year" placeholder="必填: 开课年份">
                            <el-option :key="idx" :label="year" :value="year" v-for="(year, idx) in allowedStartYears"></el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
            </el-row>
            <el-row>
                <el-col :span="8">
                    <el-form-item label="适用年级" prop="year">
                        <el-select v-model="courseModel.year" placeholder="课程针对哪个年级">
                            <el-option label="1年级" :value="1"></el-option>
                            <el-option label="2年级" :value="2"></el-option>
                            <el-option label="3年级" :value="3"></el-option>
                            <el-option label="4年级" :value="4"></el-option>
                            <el-option label="5年级" :value="5"></el-option>
                            <el-option label="6年级" :value="6"></el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
                <el-col :span="8">
                    <el-form-item label="适用学期" prop="term">
                        <el-select v-model="courseModel.term" placeholder="课程针对哪个学期">
                            <el-option label="第一学期" :value="1"></el-option>
                            <el-option label="第二学期" :value="2"></el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
                <el-col :span="8">
                    <el-form-item label="课程类型">
                        <el-select v-model="courseModel.optional" placeholder="课程类型">
                            <el-option label="选修课" value="1"></el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
            </el-row>
            <el-row>
                <el-col :span="12">
                    <el-form-item label="面向专业" prop="majors">
                        <el-select v-model="courseModel.majors" multiple filterable placeholder="请选择面向专业. 留空表示面向所有专业开放" style="width: 100%;">
                            <el-option
                                    v-for="(major, idx) in majors"
                                    :key="idx"
                                    :label="major.name"
                                    :value="major.id">
                            </el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <el-form-item label="授课教师" prop="teachers">
                        <el-select v-model="courseModel.teacher_id" filterable placeholder="请选择">
                            <el-option
                                    v-for="item in teachersInSchool"
                                    :key="item.id"
                                    :label="item.name"
                                    :value="item.id">
                            </el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
            </el-row>
            <el-row>
                <el-col :span="12">
                    <el-form-item label="最多报名" prop="max_num">
                        <el-input v-model="courseModel.max_num" placeholder="最多可容纳的学生数">
                            <template slot="append">人</template>
                        </el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <el-form-item label="开班人数" prop="open_num">
                        <el-input v-model="courseModel.open_num" placeholder="最少可以开课的学生数">
                            <template slot="append">人</template>
                        </el-input>
                    </el-form-item>
                </el-col>
            </el-row>

            <el-form-item label="课程概述">
                <el-input type="textarea" v-model="courseModel.desc" placeholder="可选"></el-input>
            </el-form-item>

            <!-- 如果是选修课, 则与专业无关, 需要制定上课的时间范围, 周内的第几天, 以及当天的第几节课 -->
            <el-row>
                <el-col :span="5">
                    <h4 class="pl-4">选修课上课时间安排: 周次</h4>
                </el-col>
                <el-col :span="19">
                    <h4 class="pl-4">上课日期, 课节与地点</h4>
                </el-col>
            </el-row>
            <el-row  v-if="!asAdmin">
                <el-col :span="5">
                    <el-row>
                        <el-form-item label="哪周上课">
                            <el-select v-model="scheduleItem.weeks" multiple placeholder="请选择在哪周上课, 可多选" style="width: 100%;">
                                <el-option
                                        v-for="weekNumber in totalWeeks"
                                        :key="weekNumber"
                                        :label="'第' + weekNumber + '周'"
                                        :value="weekNumber">
                                </el-option>
                            </el-select>
                        </el-form-item>
                    </el-row>
                </el-col>
                <el-col :span="5">
                    <el-form-item label="哪天上课">
                        <el-select v-model="scheduleItem.days" multiple placeholder="请选择哪天上课, 可多选" style="width: 100%;">
                            <el-option
                                    v-for="dayIndex in 5"
                                    :key="dayIndex"
                                    :label="'周' + dayIndex"
                                    :value="dayIndex">
                            </el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
                <el-col :span="5">
                    <el-form-item label="上课时间">
                        <el-select v-model="scheduleItem.timeSlots" multiple placeholder="请选择上课的时间段, 可多选" style="width: 100%;">
                            <el-option
                                    v-for="(timeSlot, idx) in timeSlots"
                                    :key="idx"
                                    :label="timeSlot.name"
                                    :value="timeSlot.id">
                            </el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
                <el-col :span="4">
                    <el-form-item label="教学楼">
                        <el-select v-model="scheduleItem.building_id" placeholder="请选择教学楼" style="width: 100%;">
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
                </el-col>
                <el-col :span="4">
                    <el-form-item label="教室">
                        <el-select v-model="scheduleItem.classroom_id" placeholder="请选择教室" style="width: 100%;">
                            <el-option
                                    v-for="(classroom, idx) in rooms"
                                    :key="idx"
                                    :label="classroom.name"
                                    :value="classroom.id">
                            </el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
                <el-col :span="1">
                    <el-button v-on:click="pushToSchedule" type="success" icon="el-icon-plus" circle  style="margin-left: 10px;" ></el-button>
                </el-col>
            </el-row>

            <el-row v-if="schedule.length > 0" class="mb-10">
                <el-col :span="5" v-for="(item,idx) in schedule" :key="idx" class="mr-10 mb-10">
                    <el-card class="box-card">
                        <div slot="header" class="clearfix">
                            <span v-for="week in item.weeks" :key="week">
                                <el-tag type="info" class="mr-10">第{{ week }}周</el-tag>
                            </span>
                            <el-button style="float: right; padding: 3px 0"
                                       type="danger"
                                       icon="el-icon-delete"
                                       size="mini"
                                       v-on:click="deleteScheduleItem(idx)"
                            ></el-button>
                        </div>
                        <p>
                            <span class="txt-primary">日期:</span>
                            <span v-for="day in item.days" :key="day" class="text item">
                                <el-tag size="mini" type="info" class="mr-10">周{{ day }}</el-tag>
                            </span>
                        </p>
                        <p>
                            <span class="txt-primary">时间:</span>
                            <span v-for="slot in item.timeSlots" :key="slot" class="text item">
                                <el-tag size="mini" type="info" class="mr-10">{{ getTimeSlotText(slot) }}</el-tag>
                            </span>
                        </p>
                        <p>
                            <span class="txt-primary">地点:</span>
                            <el-tag size="mini" type="info" class="mr-10">{{ item.building_name }} - {{ item.classroom_name }}</el-tag>
                        </p>
                    </el-card>
                </el-col>
            </el-row>
            <el-divider></el-divider>
            <el-form-item v-if="!asAdmin">
                <el-button type="primary" @click="saveCourse">保存</el-button>
            </el-form-item>
            <el-form-item v-if="asAdmin" label="处理意见">
                <el-input type="textarea" v-model="courseModel.reply_content" placeholder="必填"></el-input>
            </el-form-item>
            <el-form-item v-if="asAdmin">
                <el-button type="success" @click="approve">批准</el-button>
                <el-button type="danger" @click="refuse">拒绝</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
    import { searchTeachers } from '../../common/search';
    import { loadTeachersBySchool } from '../../common/welcome';
    import { Util } from '../../common/utils';
    import { loadBuildings, loadRoomsByBuilding } from '../../common/facility';
    import {
        saveElectiveCourse,
        loadElectiveCourse,
        deleteArrangement,
        approveElectiveCourse,
        refuseElectiveCourse
    } from '../../common/elective_course';

    export default {
        name: "ElectiveCourseForm",
        props:{
            courseModel:{
                type: Object,
                required: true
            },
            totalWeeks:{
                type: Number,
                required: true
            },
            timeSlots:{
                type: Array,
                required: true
            },
            majors:{
                type: Array,
                required: true
            },
            schoolId:{
                type:[Number, String],
                required: true
            },
            applicationId:{
                type:[Number, String],
                required: false,
                default: 0
            },
            asAdmin: {
                type: Boolean,
                required: false,
                default: false,
            },
            teacherId: {
                type:[Number, String],
                required: false,
                default: null
            }
        },
        watch:{
            'scheduleItem.building_id': function(newVal, oldVal){
                if(newVal !== oldVal){
                    // 去加载房间
                    this._getRoomsByBuilding(newVal);
                }
            },
            'courseModel.teacher_id': function(newVal, oldVal){
                if(newVal !== oldVal){
                    // 获取教师的名字
                    const item = Util.GetItemById(newVal, this.teachers);
                    this.courseModel.teacher_name = item.name;
                }
            },
        },
        data() {
            return {
                rules:{
                    code: [
                        { required: true, message: '请输入课程编号', trigger: 'blur' }
                    ],
                    name: [
                        { required: true, message: '请输入课程名称', trigger: 'blur' }
                    ],
                    year: [
                        { required: true, message: '请选择年级', trigger: 'change' }
                    ],
                    term: [
                        { required: true, message: '请选择学期', trigger: 'change' }
                    ],
                    open_num: [
                        { required: true, message: '请输入课程最少开班人数', trigger: 'blur' }
                    ],
                    max_num: [
                        { required: true, message: '请输入课程最多可容纳的学生数', trigger: 'blur' }
                    ],
                    start_year: [
                        { required: true, message: '开课年份', trigger: 'change' }
                    ],
                    scores: [
                        { required: true, message: '学分是必填项', trigger: 'change' }
                    ]
                },
                loading: false,
                teachers: [],
                schedule:[],
                scheduleItem:{
                    weeks:[],
                    days:[],
                    timeSlots:[],
                    building_id:'',
                    building_name:'',
                    classroom_name:'',
                    classroom_id:''
                },
                buildings:[],
                rooms:[],
                campuses:[],
                allowedStartYears:[],
                teachersInSchool:[],
            }
        },
        created(){
            this._getAllBuildings();
            this._getAllTeachers();
            const thisYear = (new Date()).getFullYear();
            this.allowedStartYears.push(thisYear);
            this.allowedStartYears.push(thisYear+1);
            this.courseModel.school_id = this.schoolId;
            if(!Util.isEmpty(this.applicationId)){
                this.getApplication(this.applicationId);
            }
        },
        mounted(){
            if(this.teacherId && !this.asAdmin){
                // 如果传入了, 表示是教师在申请
                this.courseModel.teacher_id = this.teacherId;
            }
        },
        methods: {
            // 删除已经存在的上课时间和地点项
            deleteScheduleItem: function(idx, id){
                if(this.asAdmin){
                    // 如果是负责审核的人, 那么也要从服务器删除
                    deleteArrangement(this.schedule[idx].id).then(res => {
                        if(Util.isAjaxResOk(res)){
                            this.schedule.splice(idx, 1);
                        }
                        else{
                            this.$message.error('无法删除, 请稍候再试');
                        }
                    })
                }else{
                    this.schedule.splice(idx, 1);
                }
            },
            // 批准选修课程
            approve: function(){
                if( Util.isEmpty(this.courseModel.reply_content) ){
                    // 没有填写处理意见
                    this.$message.error('请写下批准意见');
                    return;
                }
                approveElectiveCourse(this.courseModel.id).then(res => {

                });
            },
            // 拒绝选修课程
            refuse: function(){
                if( Util.isEmpty(this.courseModel.reply_content) ){
                    // 没有填写处理意见
                    this.$message.error('请写下拒绝原因');
                    return;
                }
            },
            getApplication: function(id){
                this.loading = true;
                loadElectiveCourse(id).then(res => {
                    if(Util.isAjaxResOk(res)){
                        const keys = Object.keys(res.data.data.application);
                        for(let i=0;i<keys.length;i++){
                            this.courseModel[keys[i]] = res.data.data.application[keys[i]];
                        }
                        this.schedule = res.data.data.application.schedule;
                    }
                    else{
                        this.$message.error('数据加载失败, 请稍候再试');
                    }
                    this.loading = false;
                })
            },
            saveCourse: function(){
                if(Util.isEmpty(this.schedule)){
                    this.$confirm('您还没有为此课程指定上课的时间和地点, 是否继续?', '提示', {
                        confirmButtonText: '确定',
                        cancelButtonText: '取消',
                        type: 'warning'
                    }).then(() => {
                        this._saveCourse();
                    }).catch(() => {
                        this.$message({
                            type: 'info',
                            message: '已取消'
                        });
                    });
                }else{
                    this._saveCourse();
                }
            },
            _saveCourse: function(){
                saveElectiveCourse(this.courseModel, this.schedule)
                    .then(res => {
                        if(Util.isAjaxResOk(res)){
                            this.$message('选修课保存成功');
                            this.$emit('course-saved', {course: res.data.data.course});
                            if(!this.asAdmin){
                                window.location.href = '/home';
                            }
                        }else{
                            this.$message.error(res.data.message);
                        }
                    })
                    .catch(e => {
                        this.$message.error('系统繁忙, 请稍候再试');
                    });
            },
            pushToSchedule: function(){
                if(Util.isEmpty(this.scheduleItem.weeks) || Util.isEmpty(this.scheduleItem.days) || Util.isEmpty(this.scheduleItem.timeSlots) ){
                    this.$message.error('请输入正确的周次, 日期和时间');
                    return;
                }
                const item = {
                    weeks: this.scheduleItem.weeks,
                    days: this.scheduleItem.days,
                    timeSlots: this.scheduleItem.timeSlots,
                    building_id: this.scheduleItem.building_id,
                    building_name: this.getBuildingName(this.scheduleItem.building_id),
                    classroom_id: this.scheduleItem.classroom_id,
                    classroom_name: this.getClassroomName(this.scheduleItem.classroom_id),
                };
                this.schedule.push(item);
                this.scheduleItem.weeks = [];
                this.scheduleItem.days = [];
                this.scheduleItem.timeSlots = [];
                this.scheduleItem.building_id = '';
                this.scheduleItem.building_name = '';
                this.scheduleItem.classroom_id = '';
                this.scheduleItem.classroom_name = '';
            },
            cancel: function(){
                this.$emit('cancelled');
            },
            getBuildingName: function(id){
                for(let i=0;i<this.campuses.length;i++){
                    const b = Util.GetItemById(id, this.campuses[i].buildings);
                    if(!Util.isEmpty(b)){
                        return b.name;
                    }
                }
            },
            getClassroomName: function(id){
                const c = Util.GetItemById(id, this.rooms);
                if(!Util.isEmpty(c)){
                    return c.name;
                }
            },
            getTimeSlotText: function(id){
                const c = Util.GetItemById(id, this.timeSlots);
                if(!Util.isEmpty(c)){
                    return c.name;
                }
            },
            // 远程搜索老师
            searchTeachers: function(teacherName){
                if (teacherName !== '') {
                    this.loading = true;
                    // 从服务器获取老师信息
                    searchTeachers(
                        this.schoolId,
                        teacherName,
                        this.courseModel.majors
                    ).then(res => {
                        this.loading = false;
                        if(Util.isAjaxResOk(res) && res.data.data.teachers.length > 0){
                            this.teachers = res.data.data.teachers;
                        }else{
                            this.teachers = [];
                        }
                    });
                }
            },
            // 获取学校的所有建筑, 按校区分组
            _getAllBuildings: function(){
                loadBuildings(this.schoolId).then( res => {
                    if(Util.isAjaxResOk(res)){
                        this.campuses = res.data.data.campuses;
                    }
                });
            },
            // 获取某个建筑的所有房间
            _getRoomsByBuilding: function(buildingId){
                // 获取房间时, 要根据前面一个步骤选择的时间段来进行判断.
                // 如果给定年度的, 给定学期的, 给定时间段, 给定的建筑物内,
                // 某个教室是可能被占用的, 因此被占用的不可以被返回
                loadRoomsByBuilding(buildingId).then( res => {
                    if(Util.isAjaxResOk(res)){
                        this.rooms = res.data.data.rooms;
                    }else{
                        this.rooms = [];
                    }
                });
            },
            // 获取学校的老师
            _getAllTeachers: function(){
                loadTeachersBySchool(this.schoolId).then( res => {
                    if(Util.isAjaxResOk(res)){
                        this.teachersInSchool = res.data.data.teachers;
                    }
                });
            },
        }
    }
</script>

<style scoped lang="scss">
    .txt-primary{
        color: #409EFF;
    }
    .mr-10{
        margin-right: 10px;
    }
    .mb-10{
        margin-bottom: 10px;
    }
</style>