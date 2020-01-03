@extends('layouts.app')
@section('content')
    @if(isset($needManagerNav) && $needManagerNav)
    @include('school_manager.timetable.elements.nav')
    @endif

    @include('school_manager.timetable.apps.'.$app_name)
@endsection