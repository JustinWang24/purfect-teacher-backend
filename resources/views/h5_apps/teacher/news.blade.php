@extends('layouts.h5_app')
@section('content')
    <div id="current-school-id" data-id="{{ $school->uuid }}"></div>
    <div id="{{ $appName }}" class="school-intro-container">
        <div class="header">
            <h2 class="title">{{ $pageTitle }}</h2>
        </div>
        <div class="main">


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
        </div>
    </div>
@endsection
