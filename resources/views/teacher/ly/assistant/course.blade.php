@extends('layouts.app')
@section('content')
<div id="teacher-assistant-check-in-app"  data-school="{{ session('school.id') }}">
    <!-- <div class="blade_title">我的课表</div> -->
</div>
<script>
    const school_id ="{{ session('school.id') }}"
</script>
<div id="app-init-data-holder"
     data-school="{{ session('school.id') }}"
></div>
<div id="teacherWeekTimetable" style="min-height:700px;">
</div>
@endsection
