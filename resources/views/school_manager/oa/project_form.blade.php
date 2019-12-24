<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>

@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card">
                <div class="card-head">
                    <header>项目名称: {{ $project->title }}</header>
                </div>
                <div class="card-body " id="bar-parent">
                    <form action="{{ route('school_manager.oa.project-save') }}" method="post" id="edit-project-form">
                        @csrf
                        <input type="hidden" name="project[id]" value="{{ $project->id }}" id="building-id-input">
                        <div class="form-group">
                            <label for="building-name-input">项目名称</label>
                            <input required type="text" class="form-control" id="building-name-input" value="{{ $project->title }}" placeholder="项目名称" name="project[title]">
                        </div>
                        <div class="form-group">
                            <label for="building-desc-input">项目详情</label>
                            <textarea class="form-control" name="project[content]" id="building-desc-input" cols="30" rows="10" placeholder="项目详情">{{ $project->content }}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <p>
                                    发起人:
                                </p>
                                <p>
                                    <button type="button" class="btn btn-round btn-primary">{{ $project->user->name??null }}</button>
                                </p>
                            </div>
                            <div class="col-10">
                                <p>成员: </p>
                                <p>
                                @foreach($project->members as $member)
                                    <button type="button" class="btn btn-round btn-default">{{ $member->user->name??null }}</button>
                                @endforeach
                                </p>
                            </div>
                        </div>
                        <?php
                        Anchor::Print(['text'=>trans('general.return'),'href'=>url()->previous(),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
