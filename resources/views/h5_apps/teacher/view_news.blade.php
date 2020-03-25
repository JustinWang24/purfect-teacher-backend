@extends('layouts.h5_teacher_app')
@section('content')
    <div class="school-intro-container">
        <div class="main" style="padding: 15px;">
            <h4>{{ $news->title }}</h4>
          <h5 style="padding-left: 50%" >{{$news->created_at->format('Y-m-d H:i')}}</h5>
            @foreach($news->sections as $section)
                @if($section->media_id)
                    <img src="{{ $section->content }}" style="width: 100%;">
                @else
                    <p>{{ $section->content }}</p>
                @endif
            @endforeach
        </div>
    </div>
@endsection
