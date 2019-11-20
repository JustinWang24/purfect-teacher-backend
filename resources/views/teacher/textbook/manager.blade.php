@php
    use App\Utils\UI\Anchor;
    use App\Utils\UI\Button;
    use App\User;
    use App\Utils\Misc\ConfigurationTool;
    /**
     * @var \App\User $teacher
     */
@endphp
@extends('layouts.app')
@section('content')
    <div id="app-init-data-holder"
         data-school="{{ session('school.id') }}"
         data-user="{{ $user->uuid }}"
         data-course="{{ $course->id??null }}"
         data-textbook="{{ $textbook->id??null }}"
    ></div>
    <div class="row" id="textbook-manager-app">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="row mb-4">
                <div class="col-6">
                    <el-autocomplete
                            prefix-icon="el-icon-search"
                            v-model="queryTextbook"
                            :fetch-suggestions="queryTextbooksAsync"
                            placeholder="请输入教材名进行搜索 ..."
                            @select="handleReturnedTextbookSelect"
                            value-key="name"
                            :clearable="true"
                            style="width: 90%;margin-left: 4px;"
                    ></el-autocomplete>
                </div>
                <div class="col-6">
                    <el-button type="primary" icon="el-icon-plus" v-on:click="showTextbookFormFlag = true">添加新教材</el-button>
                </div>
            </div>

            <textbooks-table
                :courses="courses"
                :books="books"
                :as-admin="{{ $user->isTeacher() || $user->isEmployee() ? 'false' : 'true' }}"
                v-on:load-textbooks="loadTextbooks"
                v-on:book-edit="editBookAction"
                v-on:connect-courses="connectCoursesAction"
            ></textbooks-table>
        </div>
        <div>
            <el-drawer
                    title="教材"
                    :visible.sync="showTextbookFormFlag"
                    direction="rtl"
                    size="50%">
                <el-form :model="textbookModel" ref="textbookForm" label-width="100px" class="textbook-form" style="margin-right: 10px;">

                    <el-row>
                        <el-col :span="16">
                            <el-form-item label="教材名称" prop="name">
                                <el-input v-model="textbookModel.name" placeholder="必填: 教材名称"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="8">
                            <el-form-item label="版本" prop="edition">
                                <el-input v-model="textbookModel.edition" placeholder="必填: 是第几个版本"></el-input>
                            </el-form-item>
                        </el-col>
                    </el-row>

                    <el-row>
                        <el-col :span="8">
                            <el-form-item label="教材作者" prop="author">
                                <el-input v-model="textbookModel.author" placeholder="必填: 教材作者"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="16">
                            <el-form-item label="出版社" prop="press">
                                <el-input v-model="textbookModel.press" placeholder="必填: 出版社"></el-input>
                            </el-form-item>
                        </el-col>
                    </el-row>

                    <el-row>
                        <el-col :span="8">
                            <el-form-item label="教材类型" prop="year">
                                <el-select v-model="textbookModel.type" placeholder="必填: 教材类型">
                                    <el-option label="专业教材" :value="1"></el-option>
                                    <el-option label="通用教材" :value="2"></el-option>
                                    <el-option label="选读教材" :value="3"></el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                        <el-col :span="8">
                            <el-form-item label="课本进价">
                                <el-input v-model="textbookModel.purchase_price" placeholder="选填: 课本进价"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="8">
                            <el-form-item label="课本零售价" prop="price">
                                <el-input v-model="textbookModel.price" placeholder="必填: 课本零售价"></el-input>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-form-item>
                        <el-button type="primary" icon="el-icon-picture" v-on:click="showFileManagerFlag=true">选择图书封面图片</el-button>
                    </el-form-item>
                    <el-form-item>
                        <div class="row">
                            <div class="col-4" v-for="(media, idx) in textbookModel.medias" :key="idx">
                                <file-preview
                                        :file-dic="media"
                                        v-on:preview-delete="selectedFileDeleted"
                                        :has-delete-button="true"
                                ></file-preview>
                            </div>
                            <div class="col-12" v-if="textbookModel.medias.length === 0">
                                <p class="text-info">还没选择图片</p>
                            </div>
                        </div>
                    </el-form-item>
                    <el-form-item label="课材简介">
                        <el-input type="textarea" v-model="textbookModel.introduce" placeholder="可选"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="saveTextbook">保 存</el-button>
                        <el-button @click="cancel">取 消</el-button>
                    </el-form-item>
                </el-form>
            </el-drawer>

            <el-dialog :title="textbookModel.name" :visible.sync="showConnectedCoursesFlag">
                <p>采用该教材的所有课程: </p>
                <el-form :model="textbookModel">
                    <el-form-item>
                        <el-select v-model="textbookModel.courses" multiple placeholder="请选择" style="width: 100%;">
                            <el-option
                                    v-for="(course, idx) in courses"
                                    :key="idx"
                                    :label="course.name"
                                    :value="course.id">
                            </el-option>
                        </el-select>
                    </el-form-item>
                </el-form>
                <div slot="footer" class="dialog-footer">
                    <el-button @click="showConnectedCoursesFlag = false">取 消</el-button>
                    <el-button type="primary" @click="updateTextbookRelatedCourses">确 定</el-button>
                </div>
            </el-dialog>

            @include(
                'reusable_elements.section.file_manager_component',
                ['pickFileHandler'=>'pickFileHandler']
            )
        </div>
    </div>
@endsection