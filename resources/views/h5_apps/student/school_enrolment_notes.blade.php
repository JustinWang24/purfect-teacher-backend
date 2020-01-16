@extends('layouts.h5_app')
@section('content')
    <div class="school-intro-container">
        <div class="header">
            <h2 class="title">{{ $pageTitle }}</h2>
        </div>
        <div class="main">
            <div class="intro-content">
            {!! $note ? $note->content : '还没有添加报名须知' !!}
            </div>
        </div>
    </div>
@endsection
