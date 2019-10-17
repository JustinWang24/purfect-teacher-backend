@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="card-box">
                <div class="card-body">
                    <!-- 学校视频 -->
                    <div class="col-sm-12 col-md-6 offset-md-2">
                        <video style="width: 100%;"
                               src="https://file-examples.com/wp-content/uploads/2017/04/file_example_MP4_1280_10MG.mp4"
                               controls autoplay="true">
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="card-box">
                <div class="card-body">
                    学校简介内容
                </div>
            </div>
        </div>
    </div>
@endsection
