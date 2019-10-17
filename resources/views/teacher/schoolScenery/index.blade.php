@extends('layouts.app')
@section('content')
    <div class="row">
        {{-- 校园视频 --}}

        @if(empty($video))

        @else
        <div class="col-sm-12 col-md-12">
            <div class="card-box">
                <div class="card-body">
                    <div class="col-sm-12 col-md-6 offset-md-2">
                        <video style="width: 100%;"
                               src="{{$video['path']}}"
                               controls autoplay="true">
                        </video>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- 校园照片 --}}
        @if(empty($img))

        @else
        <div class="col-12 col-12">
            <div class="card-box">
                <div class="card-body">
                    <div class="row">
                        @foreach($img as $image)
                         <div class="col-lg-3 col-md-6 col-12 col-sm-6">
                            <div class="blogThumb">
                                <div class="thumb-center"><img class="img-responsive" alt="user"
                                        src="{{$image['path']}}"></div>
                                <div class="course-box">
                                    <h4>校园一角</h4>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection
