@extends('layouts.h5_app')
@section('content')
<div id="current-school-id" data-id="{{ $school->uuid }}"></div>
<div id="{{ $appName }}" class="school-intro-container">
    <div class="header">
        <h2 class="title">{{ $pageTitle }}</h2>
    </div>
    <div class="main">
        <div class="intro-img">
            <el-image src="https://gss3.bdstatic.com/-Po3dSag_xI4khGkpoWK1HF6hhy/baike/c0%3Dbaike80%2C5%2C5%2C80%2C26/sign=d2bb3bedb351f819e5280b18bbdd2188/caef76094b36acafc5742b3a7fd98d1000e99c64.jpg">
            </el-image>
        </div>
        <div class="intro-content">
            <p>
                礼县职业中等专业学校，原名礼县职业技术学校，是一所省级重点职业学校，创建于1987年10月，现占地面积132.6亩，建筑面积约2.6万平方米，有教职工111人，在校学生2128人，开设文秘、电工电子、现代农艺、计算机应用等8个专业，拥有图书室、阅览室、多媒体教室及汽车维修、机械加工、电工电子、服装工艺、家政服务、计算机、电焊及烹饪等专业实训室。
            </p>
            <p>
                自建校以来,始终以“热爱专业、学有所长、修养身心、服务社会”为校训，确立了“校际联合，校企合作，资源共享，优势互补，互惠互利，共同发展”的办学思路，广泛开展职业教育，办学规模迅速扩大，办学效益日益提高，为推动经济社会的快速发展做出了应有的贡献
            </p>
            <p>
                2005年被甘肃省教育厅、甘肃省劳动和社会保障厅及甘肃省经贸委联合授予“全省职业教育先进单位”称号；2008年甘肃省教育厅授予“甘肃省职业教育工作先进集体”荣誉称号。2006年被甘肃省教育厅确定为市级重点职业学校，被甘肃省劳动和社会保障厅、甘肃省扶贫办确定为贫困地区农村劳动力转移培训示范基地,2009年被甘肃省教育厅确定为职业中专,并被认定为省级重点中等职业学校；2010年被省委省政府授予“甘肃省教育系统先进单位”荣誉称号，2011年被省教育厅授予“甘肃省职业教育招生先进单位”荣誉称号。
            </p>
        </div>
        <h3 class="sub-title">热门专业</h3>

        <div class="majors-wrap">
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
                        @{{ selectedMajor.name }}(@{{ selectedMajor.period }}年制)
                    </el-badge>
                    <span v-else>
                        @{{ selectedMajor.name }}(@{{ selectedMajor.period }}年制)
                    </span>
                    <span style="float: right"><i class="el-icon-map-location"></i>上课地点: @{{ selectedMajor.campus }}</span>
                </p>
                <p class="md-desc">
                    @{{ selectedMajor.description }}
                </p>
                <p style="text-align: center;margin-top:20px;">
                    <el-button v-if="!isPlanHasBeenApplied(selectedMajor.id)" class="showMoreButton" v-on:click="applyMajorHandler(selectedMajor)" type="primary" round>报名</el-button>
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
