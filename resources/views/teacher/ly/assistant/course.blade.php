@extends('layouts.app')
@section('content')
<div id="teacher-assistant-check-in-app">
    <div class="blade_title">课程表</div>
   
</div>

<div id="app-init-data-holder"
     data-school="{{ session('school.id') }}"
></div>
@endsection
