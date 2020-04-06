<template>
    <div class="row lecture-wrap">
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 lecture-detail">
            <div class="card">
                <div class="card-body">
                    <h3>
                        添加资料
                    </h3>
                    <hr>
                        <el-form :model="formCourseInfo" label-width="80px" class="course-form" style="margin-top: 20px;">

                            <el-form-item label="标题">
                                <el-input placeholder="必填: 标题" v-model="formCourseInfo.title"></el-input>
                            </el-form-item>
                            <el-form-item label="课节">
                                <el-select v-model="formCourseInfo.idx" placeholder="请选择" style="width: 100%">
                                    <el-option
                                        v-for="item in durations"
                                        :key="item.idx"
                                        :label="item.name"
                                        :value="item.idx">
                                    </el-option>
                                </el-select>
                            </el-form-item>

                            <el-form-item label="班级">
                                <el-checkbox-group v-model="formCourseInfo.grade_id">
                                    <el-checkbox-button v-for="item in gradesClass" :label="item.grade_name" :key="item.grade_id">{{ item.grade_name }}</el-checkbox-button>
                                </el-checkbox-group>
                            </el-form-item>
                        </el-form>
                    <hr>

                    <el-timeline>
                            <el-timeline-item
                                    v-for="(val, key) in materialTypes"
                                    :key="key"
                                    :timestamp="val.name"
                                    placement="top"
                                    size="large"
                                    icon="el-icon-folder-opened"
                            >
                            <el-card>
                                <div v-for="material in materials" :key="material.id">
                                    <div v-if="isTypeOf(material.type, typeIdx)">
                                        <p>
                                            <el-tag size="small" v-if="material.media_id === 0">
                                                外部链接
                                            </el-tag>
                                            <span>
                                        <a :href="material.url" target="_blank">
                                            {{ material.description }}
                                        </a>
                                    </span>
                                        </p>
                                        <p style="font-size: 10px;color: #cccccc;" class="text-right">
                                            上传于{{ material.created_at }} &nbsp;
                                            <el-button type="text" @click="deleteMaterial(material)">
                                                <span class="text-danger">删除</span>
                                            </el-button>
                                            <el-button type="text" @click="editMaterial(material)">
                                                <span>修改</span>
                                            </el-button>
                                        </p>
                                        <hr style="margin-top: 3px;">
                                    </div>
                                </div>
                                <p class="text-right">
                                    <el-button icon="el-icon-upload" size="mini" @click="addMaterial(val)">添加{{ val.name }}</el-button>
                                </p>
                            </el-card>
                        </el-timeline-item>
                    </el-timeline>
                    <el-button style="margin-left: 10px;" size="small" type="success" @click="saveLecture">保存</el-button>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="card" v-show="showMaterialForm">
                <div class="card-body">
                    <el-form :model="courseMaterialModel" label-width="120px" class="course-form" style="margin-top: 20px;">
                        <el-form-item label="当前类型">
                            <p class="text-primary">{{ courseMaterialModel.typeName }}</p>
                        </el-form-item>
                        <el-form-item label="课件描述">
                            <el-input placeholder="选填: 课件的描述" type="textarea" v-model="courseMaterialModel.description"></el-input>
                        </el-form-item>

                        <el-form-item label="外部链接">
                            <el-input placeholder="选填: 外部引用的URL链接地址" type="textarea" v-model="courseMaterialModel.url"></el-input>
                        </el-form-item>

                        <el-form-item label="选择课件文件">
                            <el-button type="primary" size="tiny" icon="el-icon-picture" v-on:click="showFileManagerFlag=true">
                                从我的云盘添加
                            </el-button>
                            <p v-if="selectedFile" class="mt-4">
                                已选择的文件:
                                <a :href="selectedFile.url">
                                    {{ selectedFile.description }}
                                </a>
                                &
                                <el-button type="text" @click="selectedFile = null"><span class="text-danger">放弃</span></el-button>
                            </p>
                        </el-form-item>

                        <p class="text-danger text-right">注意: 课件只能是外部链接或者云盘文件中的一种</p>
                        <el-button icon="el-icon-upload" style="margin-left: 10px;" size="small" type="success" @click="saveInfo(courseMaterialModel.index)">确定</el-button>
                        <el-button icon="el-icon-close" style="margin-left: 10px;" size="small" @click="showMaterialForm = false">取消</el-button>
                    </el-form>
                </div>
            </div>
        </div>
        <el-drawer
                title="我的易云盘"
                :visible.sync="showFileManagerFlag"
                direction="rtl"
                size="100%"
                custom-class="e-yun-pan">
            <file-manager
                    :user-uuid="userUuid"
                    :allowed-file-types="[]"
                    :pick-file="true"
                    v-on:pick-this-file="pickFileHandler"
            ></file-manager>
        </el-drawer>
    </div>
</template>

<script>
    import {Constants} from '../../../common/constants';
    import {Util} from '../../../common/utils';
    import {saveMaterial, saveLecture, loadLectureMaterials, loadMaterial, loadLectureHomework} from '../../../common/course_material';
    import FileManger from '../../fileManager/FileManager';
    import Homeworks from './Homeworks';

    export default {
        name: "Material",
        components:{
            FileManger,Homeworks
        },
        props:{
            course:{
                type: Array,
                required: true
            },
            lecture:{
                required: true
            },
            loading:{
                required:false,
                default:false
            },
            userUuid:{
                type: String,
                required: true
            },
            grades:{
                type: Array,
                required: true
            }
        },
        watch: {
            'lecture.id': function(val){
                if(val){
                    this.getLectureMaterials();
                    this.lectureModel.id = this.lecture.id;
                    this.lectureModel.title = this.lecture.title;
                    this.lectureModel.summary = this.lecture.summary;
                    this.showMaterialForm = false;
                    this.loading = false;

                    // 设置课节id
                    //this.courseMaterialModel.lecture_id = this.lecture.id;
                    this.courseMaterialModel.type = null;
                    // 设置选定的班级的默认id
                    this.selectedGrades = [];
                    this.homeworkItems = [];
                }
            }
        },
        computed:{
            currentType: function(){
               // return this.materialTypes[this.courseMaterialModel.type];
            }
        },
        data(){
            return {
                gradesClass:[
                    {
                        "grade_id": 72,
                        "grade_name": "世界史1班"
                    },
                    {
                        "grade_id": 118,
                        "grade_name": "网球教育1班"
                    }
                ], // 班级列表
                durations:[
                    {
                        "idx": 1,
                        "name": "第1节"
                    },
                    {
                        "idx": 2,
                        "name": "第2节"
                    },
                    {
                        "idx": 3,
                        "name": "第3节"
                    },
                    {
                        "idx": 4,
                        "name": "第4节"
                    },
                    {
                        "idx": 5,
                        "name": "第5节"
                    },
                    {
                        "idx": 6,
                        "name": "第6节"
                    },
                    {
                        "idx": 7,
                        "name": "第7节"
                    },
                    {
                        "idx": 8,
                        "name": "第8节"
                    },
                    {
                        "idx": 9,
                        "name": "第9节"
                    },
                    {
                        "idx": 10,
                        "name": "第10节"
                    },
                    {
                        "idx": 11,
                        "name": "第11节"
                    },
                    {
                        "idx": 12,
                        "name": "第12节"
                    },
                    {
                        "idx": 13,
                        "name": "第13节"
                    },
                    {
                        "idx": 14,
                        "name": "第14节"
                    },
                    {
                        "idx": 15,
                        "name": "第15节"
                    },
                    {
                        "idx": 16,
                        "name": "第16节"
                    },
                    {
                        "idx": 17,
                        "name": "第17节"
                    },
                    {
                        "idx": 18,
                        "name": "第18节"
                    },
                    {
                        "idx": 19,
                        "name": "第19节"
                    },
                    {
                        "idx": 20,
                        "name": "第20节"
                    }
                ], // 课节列表

                materials:[],
                materialTypes:[], // 类型
                typeClasses:[
                    '','success','info','warning','danger'
                ],
                // 添加材料
                formCourseInfo:{
                    id:null,
                    title:null, // 标题
                    teacher_id:null, // 老师id
                    course_id:null, // 课程id
                    idx:null, //课节
                    grade_id:[], // 班级id
                    type: null,
                    index: null,
                    description: null,
                    url: null,
                    media_id: 0,
                    lecture_id: null,
                    // 资料
                    materialArr:[],
                },

                // 添加材料
                courseMaterialModel:{
                    index: null,
                    type: null,
                    typeName: "", // 分类名称
                    description: null, // 描述
                    url: null, // url 地址
                    media_id: 0 // 资源id
                },
                lectureModel: {
                    id: null,
                    title: null,
                    summary: null,
                },
                showMaterialForm: false,
                showFileManagerFlag: false,
                selectedFile:null,
                loadingData: false,
                // 当前选择的班级
                selectedGrades: [],
                homeworkItems:[],
            }
        },
        created(){
            this.getMaterialTypes(); // 获取类型
            this.getCourseGradeList(); // 获取 班级 和 课节
            this.lectureModel = this.lecture;
        },
        methods: {
            // 获取类型
            getMaterialTypes: function(){
                let _that_ = this;
                axios.post(
                    '/api/study/type-list',
                    {}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        _that_.materialTypes = res.data.data;
                    }
                });
            },
            // 获取默认数据
            getCourseGradeList: function(){
                /*let _that_ = this;
                axios.post(
                    '/api/material/getCourseGradeList',
                    {course_id: 123}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        console.log(res.data.data.grades);
                        console.log(res.data.data.durations);
                        _that_.grades = res.data.data.grades;
                        _that_.durations = res.data.data.durations;
                    }
                });*/
            },
            getLectureMaterials: function(){
                this.loadingData = true;
                loadLectureMaterials(this.lecture.id).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.materials = res.data.data.materials;
                    }
                    else{
                        this.materials = [];
                    }
                    this.loadingData = false;
                })
            },
            isTypeOf: function(typeId, typeIdx){
                return typeId === typeIdx+1;
            },

            // 显示添加的课件信息
            addMaterial: function(val){
                this.courseMaterialModel.index = val.type_id;
                this.courseMaterialModel.type = !Util.isEmpty(this.formCourseInfo.materialArr[val.type_id])?this.formCourseInfo.materialArr[val.type_id].type:0;
                this.courseMaterialModel.description = !Util.isEmpty(this.formCourseInfo.materialArr[val.type_id])?this.formCourseInfo.materialArr[val.type_id].description:'';
                this.courseMaterialModel.url = !Util.isEmpty(this.formCourseInfo.materialArr[val.type_id])?this.formCourseInfo.materialArr[val.type_id].url:'';
                this.selectedFile = null;
                this.showMaterialForm = true;
                this.typeName = val.name; // 分类名称
            },
            deleteMaterial: function(material){

            },
            editMaterial: function(material){
                loadMaterial(material.id).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.courseMaterialModel = res.data.data.material;
                        this.showMaterialForm = true;
                    }
                    else{
                        this.$message.error('无法加载课件');
                    }
                })
            },
            // 确认保存到缓存中
            saveInfo: function(index){
                this.formCourseInfo.materialArr[index] = JSON.parse(JSON.stringify(this.courseMaterialModel));
            },
            _startSaving: function(){
                saveMaterial(this.courseMaterialModel).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.$message({
                            type:'success',
                            message:'保存成功'
                        });
                        // 隐藏表单
                        this.showMaterialForm = false;
                        // 从新加载materials
                        this.getLectureMaterials();
                    }
                    else{
                        this.$message.error('保存失败. ' + res.data.data);
                    }
                })
            },
            // 显示编辑可课节的标题，概要的表单
            showLectureSummaryEditForm: function(){
                this.showLectureForm = true;
                this.lectureModel.id = this.lecture.id;
            },
            // 添加数据
            saveLecture: function(){

                if(Util.isEmpty(this.formCourseInfo.title)){
                    this.$message.error('请填写标题');
                    return false;
                }
                if(Util.isEmpty(this.formCourseInfo.idx)){
                    this.$message.error('请选择课节');
                    return false;
                }
                if(Util.isEmpty(this.formCourseInfo.grade_id)){
                    this.$message.error('请选择班级');
                    return false;
                }

                // 添加数据
                let _that_ = this;
                axios.post(
                    '/api/material/addMaterial',
                    {formData:this.formCourseInfo}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.$message({
                            type:'success',
                            message: '保存成功'
                        })
                    } else {
                        this.$message({
                            type: 'info',
                            message: '操作失败,请稍后重试'
                        });
                    }
                });
            },
            // 当云盘中的文件被选择
            pickFileHandler: function(payload){
                this.selectedFile = payload.file;
                this.showFileManagerFlag = false;
            },
            // 当选择的班级发生变化, 则去更新作业的数据
            onSelectedGradesChangedHandler: function(updatedGrades){
                if(updatedGrades.length === 0){
                    this.homeworkItems = [];
                }
                else{
                    this.refreshHomeworkItems();
                }
            },
            // 刷新作业列表
            refreshHomeworkItems: function(){
                this.loadingData = true;
                loadLectureHomework(this.lecture.id, this.selectedGrades).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.homeworkItems = res.data.data.homeworks;
                    }
                    this.loadingData = false;
                })
            }
        }
    }
</script>

<style scoped lang="scss">
.lecture-wrap{
    .lecture-detail{

    }
}
</style>
