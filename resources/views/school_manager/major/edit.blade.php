<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>

@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card-box">
                <div class="card-head">
                    <header>在学校 ({{ session('school.name') }}) - 编辑专业: {{ $major->name }}</header>
                </div>
                <div class="card-body " id="bar-parent">
                    <form action="{{ route('school_manager.major.update') }}" method="post" id="edit-major-form">
                        @csrf
                        <input type="hidden" id="major-id-input" name="major[id]" value="{{ $major->id }}">
                        <input type="hidden" id="major-department-id-input" name="major[department_id]" value="{{ $major->department_id }}">
                        @include('school_manager.major._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
