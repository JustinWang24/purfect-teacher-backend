@php
    use App\Utils\UI\Anchor;
    use App\Utils\UI\Button;
@endphp
@extends('layouts.app')
@section('content')
    <div class="row" id="pipeline-flows-manager-app">
        <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5">
            <div class="card">
                <div class="card-body">
                    @foreach($groupedFlows as $type=>$flows)
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4 class="text-primary">
                                <b>{{ \App\Models\Pipeline\Flow\Flow::Types()[$type] }}</b>
                                <el-button @click="createNewFlow({{ $type }})"></el-button>
                            </h4>
                            <el-divider></el-divider>
                            <div class="row">
                                @foreach($flows as $flow)
                                <div class="col-4 mb-4 flow-box" v-on:click="loadFlowNodes({{ $flow->id }},'{{ $flow->name }}')">
                                    <img src="{{ $flow->getIconUrl() }}" width="50">
                                    <span>{{ $flow->name }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-7 col-lg-7 col-xl-7">
            <div class="card">
                <div class="card-head">
                    <header class="full-width">
                        <span class="pull-left pt-2">流程: @{{ currentFlow.name }}</span>
                    </header>
                </div>
                <div class="card-body">
                    <div class="row">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="app-init-data-holder"
         data-school="{{ session('school.id') }}"
    ></div>
@endsection
