@php
@endphp
@extends('layouts.app')
@section('content')
    <div id="app-init-data-holder"
         data-school="{{ session('school.id') }}"
    ></div>
    <div class="row" id="welcome-students-manager-app">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="row mb-4">
            </div>

            <div class="row">
            </div>
        </div>
    </div>
@endsection