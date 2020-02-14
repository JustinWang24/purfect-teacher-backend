@extends('layouts.app')
@section('content')
    <div class="row" id="teacher-assistant-check-in-app">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card">
                <div class="card-head">
                    <header class="full-width">
                        签到
                    </header>
                </div>
                <div class="card-body">
                    <p>resources/teacher/ly/assistant/check_in.blade.php</p>
                </div>
            </div>
        </div>
    </div>

    <div id="app-init-data-holder"
         data-school="{{ session('school.id') }}"
    ></div>
@endsection
