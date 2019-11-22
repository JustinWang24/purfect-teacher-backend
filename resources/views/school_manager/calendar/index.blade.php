@php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;

@endphp
@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-3 col-lg-3 col-xl-4">
            <div class="card">
                <div class="card-head">
                    <header>{{ session('school.name') }} 校历</header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <form action="" method="post"  id="edit-school-config-form">

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-9 col-lg-9 col-xl-8">
            <div class="card">
                <div class="card-head">
                    <header>
                        {{ session('school.name') }}
                    </header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-padding col-12">
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th style="width: 400px;">事件标签</th>
                                    <th style="width: 400px; text-align: center">事件内容</th>
                                    <th style="width: 100px;">事件日期</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
