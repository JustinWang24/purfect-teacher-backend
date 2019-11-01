@extends('layouts.h5_app')
@section('content')
<div id="current-school-id" data-id="{{ $school->uuid }}"></div>
<div id="{{ $appName }}" class="school-intro-container">
    <div class="header">
        <div class="close-btn">
            <i class="el-icon-close"></i>
        </div>
        <h2 class="title">{{ $pageTitle }}</h2>
    </div>
    <div class="main">
        <div class="intro-img">
            <el-image src="https://fuss10.elemecdn.com/a/3f/3302e58f9a181d2509f3dc0fa68b0jpeg.jpeg">
            </el-image>
        </div>
        <div class="intro-content">
            <p>RealTeachers Pty Ltd位于南半球最具文化气息的城市----澳大利亚墨尔本，致力于向中国大陆、台湾、越南、韩国和日本输送高品质英文母语外教。招聘澳洲外教，并经过筛选和培训，以为学生提供优质学习体验为己任。 所输出外教涵盖小学标准英语外教、幼儿园外教、音体美外教、舞台剧专业外教、中学各学科外教、专业IB外教，以及野外求生外教和咖啡师培训外教等澳洲、英国、新西兰全科科目专业外教
            </p>
            <p>
            RealTeachers Pty Ltd与墨尔本大学、莫纳什大学、国际开放学院和众多RTO有深度合作关系，每月一次的大规模开放性现场招聘说明会，已经成功搭建起中国各城市吸纳外教的最重要专业平台。所输出的外教遍布除了黑龙江和甘肃之外的所有大陆省份。
            </p>
            <p>
            另外，依托于强力的外教输出平台，RealTeachers Pty Ltd又于2017年展开了网络英文培训的业务，将优秀的外教引进到国内。目前开展的培训业务有，少儿英文网络培训一对一、一对二、一对三、一对四服务；雅思写作和口语培训；青少年游学前培训；青少年留学前培训、成人移民澳洲文化特训，外教英文一对一等。
            </p>
        </div>
        <h3 class="sub-title">热门专业</h3>

        <div class="majors-wrap">
            <el-card shadow="always" v-for="(major, idx) in hotMajors" :key="idx">
                <el-row>
                    <el-col :span="6">
                        <p class="major-name">@{{ major.name }}</p>
                    </el-col>
                    <el-col :span="6">
                        <p class="stat">
                            <span class="apply">@{{ major.seats }}</span>/<span class="seats">@{{ major.seats }}</span>
                        </p>
                    </el-col>
                    <el-col :span="6">
                        <p class="fee">
                            学费: @{{ major.fee }}/年
                        </p>
                    </el-col>
                    <el-col :span="6">
                        <el-button style="float: right;font-size: 12px;padding: 4px 15px;" size="mini" type="primary" round v-on:click="applyMajorHandler(major)">报名</el-button>
                    </el-col>
                </el-row>
            </el-card>

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
                direction="rtl">
            <div class="major-detail-wrapper" v-if="selectedMajor">
                <p class="md-name">@{{ selectedMajor.name }}</p>
                <p class="md-desc">
                    @{{ selectedMajor.description }}
                </p>
                <p style="text-align: center;margin-top:20px;">
                    <el-button class="showMoreButton" v-on:click="applyMajorHandler(selectedMajor)" type="primary" round>报名</el-button>
                </p>
            </div>
        </el-drawer>

        <el-drawer
                :title="'报名:' + (selectedMajor ? selectedMajor.name : '')"
                size="100%"
                :visible.sync="showRegistrationFormFlag"
                direction="rtl">
            <major-registration-form
                :major="selectedMajor"
            ></major-registration-form>
        </el-drawer>
    </div>
</div>
@endsection
