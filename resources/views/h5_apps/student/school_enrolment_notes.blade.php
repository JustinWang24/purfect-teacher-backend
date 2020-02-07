@extends('layouts.h5_app')
@section('content')
    <div class="school-intro-container">
        <div class="main">
            <div class="intro-content">
            {!! $note ? $note->content : '还没有添加报名须知' !!}
            </div>
        </div>
    </div>
@endsection
