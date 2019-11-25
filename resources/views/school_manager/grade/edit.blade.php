@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card-box">
                <div class="card-head">
                    <header>在学校 ({{ session('school.name') }}) - 编辑专业: {{ $parent->name }}</header>
                </div>
                <div class="card-body " id="bar-parent">
                    <form action="{{ route('school_manager.grade.update') }}" method="post" id="edit-grade-form">
                        @csrf
                        <input type="hidden" id="grade-id-input" name="grade[id]" value="{{ $grade->id }}">
                        <input type="hidden" id="grade-major-id-input" name="grade[major_id]" value="{{ $parent->id }}">
                        @include('school_manager.grade._form')

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
