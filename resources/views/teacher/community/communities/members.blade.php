@extends('layouts.app')
@section('content')

    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card-box">

                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>序号</th>
                                    <th>手机号</th>
                                    <th>姓名</th>
                                    <th>申请理由</th>
                                    <th>状态</th>
                                </tr>
                                </thead>
                                @foreach($members as $key => $val)
                                    <tbody>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $val->user->mobile }}</td>
                                        <td>{{ $val->user->name }}</td>
                                        <td>{{ $val->reason }}</td>
                                        <td>{{ $val->statusText() }}</td>

                                    </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
