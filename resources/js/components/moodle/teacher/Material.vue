<template>
    <div class="row lecture-wrap">
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 lecture-detail">
            <div class="card">
                <div class="card-body">
                    <h3>
                        添加资料
                    </h3>
                    <hr>
                        <el-form :model="lectureModel" label-width="80px" class="course-form" style="margin-top: 20px;">


                            <el-form-item label="标题">
                                <el-input placeholder="必填: 标题" v-model="lectureModel.title"></el-input>
                            </el-form-item>

                            <el-form-item label="概要">
                                <el-input placeholder="必填: 概要" type="textarea" v-model="lectureModel.summary"></el-input>
                            </el-form-item>
                            <el-button style="margin-left: 10px;" size="small" type="success" @click="saveLecture">保存</el-button>
                        </el-form>
                    <hr>

                    <el-timeline>
                        <el-timeline-item
                                v-for="(type, typeIdx) in materialTypes"
                                :key="typeIdx"
                                :timestamp="type"
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
                                    <el-button icon="el-icon-upload" size="mini" @click="addMaterial(typeIdx)">添加{{ type }}</el-button>
                                </p>
                            </el-card>
                        </el-timeline-item>
                    </el-timeline>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="card" v-show="showMaterialForm">
                <div class="card-body">
                    <el-form :model="courseMaterialModel" label-width="120px" class="course-form" style="margin-top: 20px;">
                        <el-form-item label="当前类型">
                            <p class="text-primary">{{ currentType }}</p>
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
                                &nbsp;
                                <el-button type="text" @click="selectedFile = null"><span class="text-danger">放弃</span></el-button>
                            </p>
                        </el-form-item>
                        <p class="text-danger text-right">注意: 课件只能是外部链接或者云盘文件中的一种</p>
                        <el-button icon="el-icon-upload" style="margin-left: 10px;" size="small" type="success" @click="submitUpload">保存</el-button>
                        <el-button icon="el-icon-close" style="margin-left: 10px;" size="small" @click="showMaterialForm = false">取消</el-button>
                    </el-form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h3>
                        <i v-show="loading || loadingData" class="el-icon-loading"></i>
                        课后作业
                        <el-button type="text" icon="el-icon-refresh-right" @click="refreshHomeworkItems">手动刷新</el-button>
                    </h3>
                    <hr>
                    <p>
                        <el-checkbox-group v-model="selectedGrades" size="medium" @change="onSelectedGradesChangedHandler">
                            <el-checkbox-button v-for="g in grades" :label="g.id" :key="g.id">{{g.name}}</el-checkbox-button>
                        </el-checkbox-group>
                    </p>
                    <hr>
                    <homeworks :items="homeworkItems"></homeworks>
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
                    this.courseMaterialModel.lecture_id = this.lecture.id;
                    this.courseMaterialModel.type = null;
                    // 设置选定的班级的默认id
                    this.selectedGrades = [];
                    this.homeworkItems = [];
                }
            }
        },
        computed:{
            currentType: function(){
                return this.materialTypes[this.courseMaterialModel.type-1];
            }
        },
        data(){
            return {
                grades:[], // 班级列表
                durations:[], // 课节列表

                materials:[],
                materialTypes:[],
                typeClasses:[
                    '','success','info','warning','danger'
                ],
                courseMaterialModel:{
                    id:null,
                    teacher_id:null,
                    course_id:null,
                    type: null,
                    index: null,
                    description: null,
                    url: null,
                    media_id: 0,
                    lecture_id: null,
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
            console.log('zkkkkkkkkkkkkkkkkkkkkkkkkk');
            this.getCourseGradeList(); // 获取 班级 和 课节
            this.materialTypes = Constants.COURSE_MATERIAL_TYPES_TEXT;
            this.lectureModel = this.lecture;
        },
        methods: {
            getCourseGradeList: function(){
                axios.post(
                    '/api/material/getCourseGradeList',
                    {course_id: 23}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.grades = res.data.data.grades;
                        this.durations = res.data.data.durations;
                    }
                });
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
            addMaterial: function(typeIdx){
                // 添加新的课件
                this.resetMaterialForm(typeIdx+1);
                this.showMaterialForm = true;
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
            submitUpload: function(){
                if(Util.isEmpty(this.courseMaterialModel.index)){
                    this.$message.error('请指定课件将用于第几节课');
                    return false;
                }
                if(Util.isEmpty(this.courseMaterialModel.type)){
                    this.$message.error('请指定课件的类型');
                    return false;
                }
                if(Util.isEmpty(this.courseMaterialModel.description)){
                    this.$message.error('请描述一下本课件的用途');
                    return false;
                }
                if(Util.isEmpty(this.selectedFile)){
                    this.$confirm('本课件没有关联任何文件, 是否继续?', '提示', {
                        confirmButtonText: '确定. 不需要关联任何文件',
                        cancelButtonText: '取消',
                        type: 'warning'
                    }).then(() => {
                        // 继续上传即可
                        this._startSaving();
                    }).catch(() => {
                        this.$message({
                            type: 'info',
                            message: '已取消保存操作'
                        });
                        return false;
                    });
                }
                else{
                    this.courseMaterialModel.url = this.selectedFile.url;
                    this.courseMaterialModel.media_id = this.selectedFile.id;
                    this._startSaving();
                }
            },
            resetMaterialForm: function(type){
                this.courseMaterialModel.id = null;
                this.courseMaterialModel.index = this.lecture.idx;
                this.courseMaterialModel.type = type;
                this.courseMaterialModel.description = null;
                this.courseMaterialModel.url = null;
                this.courseMaterialModel.media_id = 0;
                this.courseMaterialModel.teacher_id = this.lecture.teacher_id;
                this.courseMaterialModel.course_id = this.lecture.course_id;
                this.selectedFile = null;
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
            saveLecture: function(){
                saveLecture(this.lectureModel).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.$message({
                            type:'success',
                            message: '保存成功'
                        })
                    }
                    this.showLectureForm = false;
                })
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
