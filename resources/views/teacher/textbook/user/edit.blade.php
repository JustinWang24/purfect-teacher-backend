<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>
@extends('layouts.app')
@section('content')
 <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card-box">
                <div class="card-head">
                    <header>基本信息</header>
                </div>
                <div class="card-body">
                    <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="application-user-name">姓名:</label>
                                    {{ $gradeUser->user->name }}
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="application-user-name">学号:</label>
                                    {{ $gradeUser->user->profile->student_number }}
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="application-user-name">学院:</label>
                                    {{ $gradeUser->institute->name }}
                                </div>
                            </div>


                            <div class="col-3">
                                <div class="form-group">
                                    <label for="application-user-name">年级:</label>
                                    {{ $gradeUser->grade->year }}级
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="application-user-name">专业:</label>
                                    {{ $gradeUser->major->name }}
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="application-user-name">班级:</label>
                                    {{ $gradeUser->grade->name }}
                                </div>
                            </div>

                        </div>
                </div>

                <div class="card-head">
                    <header>教材领取列表</header>
                </div>

                 <div class="card-body">
                     <div class="row">
                         <div class="table-responsive">
                            <form action="{{ route('school_manager.textbook.submit') }}" method="post">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $gradeUser->user->id }}">
                                <input type="hidden" name="year" value="{{ $year }}">
                            <table
                            class="table table-striped table-bordered
                            table-hover table-checkable order-column
                            valign-middle">
                            <thead>
                            <tr>
                            <th><input type="checkbox" id="check"> </th>
                            <th>序号</th>
                            <th>教材</th>
                            <th>出版社</th>
                            <th>状态</th>
                            <th>领取时间</th>
                            <th>操作</th>
                            </tr>
                            </thead>

                            <tbody>

                            @foreach($textbooks as $key => $val)
                            <tr>
                            <td>
                              @if(!$val->getTime)
                                  <input type="checkbox" name="textbook_id[]" value="{{$val->id}}">
                              @endif
                            </td>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $val->name }}</td>
                            <td>{{ $val->press }}</td>
                            <td>{{ $val->status }}</td>
                            <td>{{ $val->getTime }}</td>
                            <td>
                              @if(!$val->getTime)
                                  {{ \App\Utils\UI\Anchor::Print(['text'=>'领取','class'=>'btn-edit-facility',
                            'href'=>route('school_manager.textbook.get',['textbook_id'=>$val->id,
                            'user_id'=>$gradeUser->user->id,'year'=>$year])],
                            \App\Utils\UI\Button::TYPE_DEFAULT,'add') }}
                              @endif
                            </td>
                            </tr>
                            @endforeach
                            </tbody>
                            </table>
                            <?php
                            Button::Print(['id'=>'btn-create-application','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                            ?>
                            <?php
                            Anchor::Print(['text'=>trans('general.return'),'href'=>route('school_manager.textbook.grade',['type'=>1,'by'=>'grade','uuid'=>$uuid]),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                            ?>
                            </form>
                         </div>
                     </div>
                 </div>


            </div>
        </div>
 </div>

 <script>
    document.getElementById("check").onclick = function(){
        var checked = document.getElementById("check").checked;
        var checkson = document.getElementsByName("textbook_id[]");
        if(checked){
            for(var i = 0; i < checkson.length ;i++){
                checkson[i].checked = true;
            }
        }else{
            for(var i = 0; i < checkson.length ;i++){
                checkson[i].checked = false;
            }
        }
    }
</script>
@endsection
