@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card-box">
                <div class="card-head">
                    <header class="full-width">
                        {{ session('school.name') }} {{ $campus->name ?? '' }}
                        <a href="{{ route('school_manager.school.view') }}" class="btn btn-default pull-right">
                            返回 <i class="fa fa-arrow-circle-left"></i>
                        </a>
                    </header>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="table-padding col-12 pt-0">
                            @if(isset($campus))
                                <a href="{{ route('school_manager.institute.add',['uuid'=>$campus->id]) }}" class="btn btn-primary " id="btn-create-institute-from-campus">
                                    创建新学院 <i class="fa fa-plus"></i>
                                </a>
                            @endif
                            @include('school_manager.school.reusable.nav',['highlight'=>'institute'])
                        </div>
                        <div class="table-responsive">
                            @include('school_manager.school.reusable.tables.institute',['institutes'=>$institutes])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
