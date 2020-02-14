@extends('layouts.app')
@section('content')
    <div class="row" id="teacher-oa-internal_messages-app">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card">
                <div class="card-head">
                    <header class="full-width">
                        内部信
                    </header>
                </div>
                <div class="card-body">
                    <p>resources/teacher/ly/oa/internal_messages.blade.php</p>
                </div>
            </div>
        </div>
    </div>

    <div id="app-init-data-holder"
         data-school="{{ session('school.id') }}"
    ></div>
@endsection
