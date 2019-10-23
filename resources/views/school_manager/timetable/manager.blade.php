<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
use App\User;
?>
@extends('layouts.app')
@section('content')
    <div class="row" id="school-timetable-manager">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card-box">
                <div class="card-head">
                    <header>{{ session('school.name') }} 课程表管理</header>
                </div>

                <div class="card-body">
                    <div class="row">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection