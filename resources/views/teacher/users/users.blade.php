<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
use App\User;
?>
@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card">
                <div class="card-head">
                    <header class="full-width">
                        {{ $parent->name??session('school.name') }}(未认证用户总数: {{ $students->total() }})
                    </header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-padding col-12 pt-0">
                            @include('school_manager.school.reusable.nav',['highlight'=>'users'])
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>头像</th>
                                    <th>姓名</th>
                                    <th>联系电话</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($students as $index=> $gradeUser)
                                    @php
                                        /** @var \App\Models\Users\GradeUser $gradeUser */
                                    @endphp
                                    <tr>
                                        <td>{{$index+1}}</td>
                                        <td>
                                            <img src="{{ $gradeUser->studentProfile->avatar??null }}" style="width: 60px;border-radius: 50%;">
                                        </td>
                                        <td>
                                            {{ $gradeUser->user->name ?? 'n.a' }}
                                            @if(isset($parent) && get_class($parent) === 'App\Models\Schools\Grade')
                                                @if(isset($parent->gradeManager->monitor_id))
                                                    @if($parent->gradeManager->monitor_id === $gradeUser->user_id)
                                                        <span class="text-primary">(班长)</span>
                                                    @endif
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            {{ $gradeUser->user->mobile }}
                                            {{ $gradeUser->user->getStatusText() }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                {{ isset($appendedParams) ? $students->appends($appendedParams)->links() : $students->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
