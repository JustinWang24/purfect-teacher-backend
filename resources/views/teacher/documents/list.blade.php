@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card">

                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>序号</th>
                                    <th>公文流转</th>
                                    <th>步骤</th>
                                    <th>已发公文</th>
                                </tr>
                                </thead>
                                @foreach($list as $key => $val)
                                    @php
/** @var \App\Utils\Pipeline\IFlow $val */
                                    @endphp
                                    <tbody>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $val->name }}</td>
                                    <td>
                                        @foreach($val->nodes as $node)
                                        <span class="text-primary">{{ $node->name }}</span>&nbsp;
                                        @endforeach
                                    </td>
                                    @php
                                        $userFlows = (new \App\Dao\Pipeline\UserFlowDao())->getByFlowId($val->id);
                                    @endphp
                                    <td>
                                        @foreach($userFlows as $userFlow)
                                        <a href="{{ route('pipeline.flow-view-history',['user_flow_id'=>$userFlow->id]) }}">
                                            <p class="text-primary">{{ $userFlow->user_name }}, 状态: {{ $userFlow->status ? '完成' : '流转中' }}</p>
                                        </a>
                                        @endforeach
                                    </td>
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