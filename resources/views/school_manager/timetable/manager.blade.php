<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
use App\User;
?>
@extends('layouts.app')
@section('content')
    @include('school_manager.timetable.elements.nav')
    @include('school_manager.timetable.apps.'.$app_name)
@endsection