<template>
    <div class="row lecture-wrap">
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 lecture-detail">
            <div class="card">
                <div class="card-body">
                    <h3>第{{ lecture.idx }}节: {{ lecture.title }}</h3>
                    <el-timeline>
                        <el-timeline-item v-for="(type, typeIdx) in materialTypes" :key="typeIdx" :timestamp="type" placement="top">
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
                                    <el-button size="mini" @click="addMaterial(typeIdx)">添加{{ type }}</el-button>
                                </p>
                            </el-card>
                        </el-timeline-item>
                    </el-timeline>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="card">
                <div class="card-body" v-show="showMaterialForm">
                    <el-form :model="courseMaterialModel" label-width="120px" class="course-form" style="margin-top: 20px;">
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
                        <el-button style="margin-left: 10px;" size="small" type="success" @click="submitUpload">保存</el-button>
                    </el-form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {Constants} from '../../../common/constants';
    import {Util} from '../../../common/utils';

    export default {
        name: "Lecture",
        props:{
            lecture:{
                required: true
            }
        },
        watch: {
            'lecture.id': function(val){
                if(val){
                    this.getLectureMaterials();
                }
            }
        },
        data(){
            return {
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
                    media_id: 0
                },
                showMaterialForm: false,
                selectedFile:null,
            }
        },
        created(){
            this.materialTypes = [
                Constants.COURSE_MATERIAL_TYPES.TYPE_PRE_TXT,
                Constants.COURSE_MATERIAL_TYPES.TYPE_LECTURE_TXT,
                Constants.COURSE_MATERIAL_TYPES.TYPE_AFTER_TXT,
                Constants.COURSE_MATERIAL_TYPES.TYPE_HOMEWORK_TXT,
                Constants.COURSE_MATERIAL_TYPES.TYPE_EXAM_TXT,
            ];
        },
        methods: {
            getLectureMaterials: function(){
                console.log('Get lecture materials', this.lecture.id);
            },
            isTypeOf: function(typeId, typeIdx){
                return typeId === typeIdx+1;
            },
            addMaterial: function(typeIdx){

            },
            deleteMaterial: function(material){

            },
            editMaterial: function(material){

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
            resetMaterialForm: function(index){
                this.courseMaterialModel.id = null;
                this.courseMaterialModel.index = index;
                this.courseMaterialModel.type = null;
                this.courseMaterialModel.description = null;
                this.courseMaterialModel.url = null;
                this.courseMaterialModel.media_id = 0;
                this.selectedFile = null;
            },
            _startSaving: function(){
                saveMaterial(this.courseMaterialModel).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.$message({
                            type:'success',
                            message:'保存成功'
                        });
                        window.location.reload();
                    }
                    else{
                        this.$message.error('保存失败. ' + res.data.data);
                    }
                })
            },
        }
    }
</script>

<style scoped lang="scss">
.lecture-wrap{
    .lecture-detail{

    }
}
</style>