@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card">
                <div class="card-head">
                    <header>
                        {{ $major->name }}校区 - 教材汇总表
                        <a href="{{ route('teacher.textbook.loadMajorTextbook',['major_id'=>$major->id,'download'=>true]) }}" class="btn btn-primary">
                            下载 <i class="fa fa-download"></i>
                        </a>&nbsp;
                    </header>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            @include('teacher.textbook.elements.table_by_major')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection