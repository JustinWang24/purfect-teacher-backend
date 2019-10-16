@extends('layouts.app')
@section('content')
<div class="page-content">
    <div class="page-bar">
        <div class="page-title-breadcrumb">
            <div class="pull-left">
                <div class="page-title">校园相册</div>
            </div>
            <ol class="breadcrumb page-breadcrumb pull-right">
                <li><i class="fa fa-home"></i>&nbsp;<a class="parent-item" href="index.html">Home</a>&nbsp;<i class="fa fa-angle-right"></i></li>
                <li class="active">Dashboard</li>
            </ol>
        </div>
        </div>
        <div class="row">
            <div class="col-12 col-8">
                <div class="card-box">
                        <div class="card-body">
                            <div class="col-sm-12 col-md-8 offset-md-2">
                                <video style="width: 100%;" src="https://file-examples.com/wp-content/uploads/2017/04/file_example_MP4_640_3MG.mp4" controls autoplay="true">
                                </video>
                            </div>
                        </div>
                </div>
            </div>

            {{-- 校园照片 --}}
            <div class="col-12 col-12">
                <div class="card-box">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-12 col-sm-6">
                                <div class="blogThumb">
                                    <div class="thumb-center"><img class="img-responsive" alt="user"
                                            src="http://www.mangowed.com/uploads/allimg/140316/1-14031620214K56.jpg"></div>
                                    <div class="course-box">
                                        <h4>校园一角</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
              </div>
           </div>
        </div>
    </div>
</div>
@endsection
