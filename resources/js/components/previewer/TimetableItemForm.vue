<template>
    <div class="timetable-item-form-wrap">
        <el-steps :active="currentStep" finish-status="success" simple style="margin-bottom: 20px">
            <el-step title="时间" ></el-step>
            <el-step title="地点" ></el-step>
            <el-step title="班级" ></el-step>
            <el-step title="课程" ></el-step>
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
            }
        },
        data() {
            return {
                currentStep: 1,
                timeTableItem: {
                    year:'',
                    term:'1',
                    repeat_unit:'1',
                    weekday_index:'1',
                    time_slot_id:'',
                    // 地点
                    building_id:'',
                    room_id:'',
                },
                timeSlots: [],
                campuses: [],
                rooms: [],
            }
        },

        watch: {
            'timeTableItem.building_id': function(newVal, oldVal){
                if(newVal !== oldVal){
                    // 去加载房间
                    this._getRoomsByBuilding(newVal);
                }
            }
        },

        created(){
            this._getAllBuildings();
            this._getAllTimeSlots();
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