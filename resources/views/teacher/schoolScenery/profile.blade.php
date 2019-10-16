@extends('layouts.app')
@section('content')
<div class="page-content">
    <div class="page-bar">
        <div class="page-title-breadcrumb">
            <div class=" pull-left">
                <div class="page-title">学校简介</div>
            </div>
            <ol class="breadcrumb page-breadcrumb pull-right">
                <li><i class="fa fa-home"></i>&nbsp;<a class="parent-item" href="index.html">Home</a>&nbsp;<i class="fa fa-angle-right"></i></li>
                <li class="active">Dashboard</li>
            </ol>
        </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="card-box">
                        <div class="card-body">
                            <!-- 学校视频 -->
                            <div class="col-sm-12 col-md-8 offset-md-2">
                                <video style="width: 100%;" src="https://file-examples.com/wp-content/uploads/2017/04/file_example_MP4_1280_10MG.mp4" controls autoplay="true">
                                </video>
                            </div>
                            <div class="row">
                                学校简介内容
                            </div>
                     </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
