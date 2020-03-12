<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
use App\User;
?>
@extends('layouts.app')
@section('content')
<!-- 列表页1 -->
<script>
  let school_id = {{ session('school.id') }}
</script>
<div id="teacherAttendanceManager">
</div>
@endsection