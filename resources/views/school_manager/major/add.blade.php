@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card-box">
                <div class="card-head">
                    <header>在学校 ({{ session('school.name') }}) - {{ $department->name }} 创建新专业</header>
                </div>
                <div class="card-body " id="bar-parent">
                    <form action="{{ route('school_manager.major.update') }}" method="post" id="add-major-form">
                        @include('school_manager.major._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
