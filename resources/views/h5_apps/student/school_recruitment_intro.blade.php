@extends('layouts.h5_app')
@section('content')
    <div class="school-intro-container">
        <div class="main">
            <div class="intro-content">
                {!! $config ? $config->recruitment_intro : '还没有添加招生简章' !!}
            </div>
        </div>
    </div>
@endsection
