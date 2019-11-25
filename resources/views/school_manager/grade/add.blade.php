

@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card-box">
                <div class="card-head">
                    <header>在学校 ({{ session('school.name') }}) - {{ $parent->name }} 创建新班级</header>
                </div>
                <div class="card-body " id="bar-parent">
                    <form action="{{ route('school_manager.grade.update') }}" method="post" id="add-grade-form">
                        @include('school_manager.grade._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
