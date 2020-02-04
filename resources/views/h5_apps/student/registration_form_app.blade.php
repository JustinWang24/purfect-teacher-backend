@extends('layouts.h5_app')
@section('content')
<div id="current-school-id" data-id="{{ $school->uuid }}" data-planid="{{ isset($plan)?$plan->id:null }}" data-planname="{{ isset($plan)?$plan->major_name:null }}"></div>
<div id="{{ $appName }}" class="school-intro-container">
    <div class="header">
        <h2 class="title">{{ $pageTitle }}</h2>
    </div>
    <div class="main">
        <div class="majors-wrap" style="display: none;">
            <div v-for="(major, idx) in majors" :key="idx">
                <el-card shadow="always" v-if="major.hot === true">
                    <el-row>
                        <el-col :span="6">
                            <p class="major-name" v-on:click="showMajorDetailHandler(major)">@{{ major.name }}</p>
                        </el-col>
                        <el-col :span="6">
                            <p class="stat">
                                <span class="enrolled">@{{ major.enrolled }}</span>/<span class="seats">@{{ major.seats }}</span>
                            </p>
                        </el-col>
                        <el-col :span="6">
                            <p class="fee">
                                学费: @{{ major.fee }}/年
                            </p>
                        </el-col>
                        <el-col :span="6">
                            <el-button v-if="!major.applied" style="float: right;font-size: 12px;padding: 4px 15px;" size="mini" type="primary" round v-on:click="applyMajorHandler(major)">报名</el-button>
                            <p class="status-text" v-else>@{{ major.applied }}</p>
                        </el-col>
                    </el-row>
                </el-card>
            </div>
            <p style="text-align: center;margin-top:20px;">
                <el-button class="showMoreButton" v-on:click="showAllMajors" type="primary" round>查看更多</el-button>
            </p>
        </div>

        <el-drawer
                title="专业介绍"
                size="100%"
                :visible.sync="showAllMajorsFlag"
                direction="ltr">
            <major-cards
                    :majors="majors"
                    v-on:apply-major="applyMajorHandler"
                    v-on:show-major-detail="showMajorDetailHandler"
            ></major-cards>
        </el-drawer>

        <el-drawer
                title="专业详情"
                size="100%"
                :visible.sync="showMajorDetailFlag"
                v-on:closed="showAllMajors"
                direction="rtl">
            <div class="major-detail-wrapper" v-if="selectedMajor">
                <el-image style="margin-bottom: 12px;" src="https://gss0.bdstatic.com/-4o3dSag_xI4khGkpoWK1HF6hhy/baike/c0%3Dbaike150%2C5%2C5%2C150%2C50/sign=823085820233874488c8272e3066b29c/ac345982b2b7d0a278e0df4fc5ef76094b369a33.jpg"></el-image>
                <p class="md-name">
                    <el-badge v-if="selectedMajor.hot" value="热门" class="item">
                        <span style="font-size: 18px;">@{{ selectedMajor.name }}(@{{ selectedMajor.period }}年制)</span>
                    </el-badge>
                    <span v-else>
                        @{{ selectedMajor.name }}(@{{ selectedMajor.period }}年制)
                    </span>
                    <p><i class="el-icon-map-location"></i>上课地点: @{{ selectedMajor.campus }}</p>
                </p>
                <el-divider></el-divider>
                <p class="md-name"><i class="el-icon-reading"></i>&nbsp;详情介绍</p>
                <p class="md-desc">
                    @{{ selectedMajor.description }}
                </p>
                <p style="text-align: center;margin-top:20px;">
                    <el-button v-if="!isPlanHasBeenApplied(selectedMajor.id)" class="showMoreButton" v-on:click="applyMajorNewHandler(selectedMajor)" type="primary" round>报名</el-button>
                </p>
                <div v-if="selectedMajor.courses">
                    <el-divider></el-divider>
                    <p class="md-name"><i class="el-icon-trophy"></i>&nbsp;学习的专业课包括:</p>
                    <p>
                        <el-tag effect="plain" style="margin: 5px;" v-for="(course, idx) in selectedMajor.courses" :key="idx">@{{ course.name }}</el-tag>
                    </p>
                </div>
                <el-divider></el-divider>
                <p class="md-name"><i class="el-icon-help"></i>&nbsp;招生对象</p>
                <p class="md-desc">@{{ selectedMajor.target_students }}</p>

                <el-divider></el-divider>
                <p class="md-name"><i class="el-icon-help"></i>&nbsp;报名条件</p>
                <p class="md-desc">@{{ selectedMajor.student_requirements }}</p>

                <el-divider></el-divider>
                <p class="md-name"><i class="el-icon-help"></i>&nbsp;录取方式</p>
                <p class="md-desc">@{{ selectedMajor.how_to_enrol }}</p>

                <el-divider></el-divider>
                <p class="md-name"><i class="el-icon-help"></i>&nbsp;毕业后的择业方向</p>
                <p class="md-desc">@{{ selectedMajor.future }}</p>
            </div>
        </el-drawer>

        <el-drawer
                :title="'报名:' + (selectedMajor ? selectedMajor.name + '(' + selectedMajor.period +'年制)' : '')"
                size="100%"
                :visible.sync="showRegistrationFormFlag"
                direction="rtl">
            <major-registration-form
                :major="selectedMajor"
                :registration-form="registrationForm"
                v-on:form-saved-success="formSavedSuccessHandler"
                v-on:form-saved-failed="formSavedFailedHandler"
            ></major-registration-form>
        </el-drawer>
    </div>
</div>
@endsection
