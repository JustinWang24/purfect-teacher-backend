<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>

@extends('layouts.app')
@section('content')
    <div id="school-add-student-app">
      <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card">
                <div class="card-head">
                    <header class="full-width">
                        创建新的学生档案
                    </header>
                </div>
                <div class="card-body">
                    <form action="{{ route('school_manager.student.update') }}" method="post">
                        @csrf
                        <input type="hidden" name="profile[school_id]" value="{{ session('school.id') }}">
                        <div class="row">
                            <div class="col-2">
                                <div class="form-group">
                                    <label>姓名</label>
                                    <input required type="text" class="form-control" value="" placeholder="学生姓名" name="user[name]">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label>手机号码</label>
                                    <input required type="text" class="form-control" value="" placeholder="手机号码" name="user[mobile]">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>身份证号</label>
                                    <input required type="text" class="form-control" value="" placeholder="身份证号" name="profile[id_number]">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label>学号</label>
                                    <input required type="text" class="form-control" value="" placeholder="必填: 分配的学号" name="profile[student_number]">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label>专业</label>
                                    <select name="major[id]" class="form-control" @click="getMajors()" v-model="majorId">
                                      <option value="">请选择</option>
                                      <option v-for="(item, key) in majors"  :value="item.id" >@{{item.name}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label>招生年度/年级</label>
                                    <select required type="date" class="form-control" name="profile[year]" v-model="year" @click="getGrades()">
                                         <option value="">请选择</option>
                                        @foreach(\App\Utils\Time\GradeAndYearUtil::GetAllYears() as $year)
                                            <option {{ intval(date('Y'))===$year+1 ? 'selected':null }} value="{{ $year+1 }}">{{ $year+1 }}年</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label>班级</label>
                                    <select name="grade[id]" class="form-control" @click="getGrades()">
                                      <option value="">请选择</option>
                                      <option v-for="(item, key) in grades"  :value="item.id" >@{{item.name}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label>是否建档立卡</label>
                                    <select required type="date" class="form-control" name="profile[create_file]">
                                        <option value="0">否</option>
                                        <option value="1">是</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label>学生编号</label>
                                    <input required type="text" class="form-control" value="" placeholder="必填: 学生编号" name="profile[serial_number]">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>政治面貌</label>
                                    <input required type="text" class="form-control" value="" placeholder="政治面貌" name="profile[political_name]">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>民族</label>
                                    <input required type="text" class="form-control" value="" placeholder="民族" name="profile[nation_name]">
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h4>个人资料/履历</h4>
                        <hr>

                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label>性别</label>
                                    <select required type="date" class="form-control" name="profile[gender]">
                                        <option value="1">男</option>
                                        <option value="0">女</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label>户籍性质</label>
                                    <select required type="date" class="form-control" name="profile[resident_type]">
                                        <option value="农业户口">农业户口</option>
                                        <option value="非农业户口">非农业户口</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label>户籍所在乡镇</label>
                                    <input required type="text" class="form-control" value="" placeholder="户籍所在乡镇" name="profile[resident_suburb]">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label>户籍所在村</label>
                                    <input required type="text" class="form-control" value="" placeholder="户籍所在村" name="profile[resident_village]">
                                </div>
                            </div>
                        </div>



                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label>是否农村低保</label>
                                    <select required type="date" class="form-control" name="profile[special_support]">
                                        <option value="0">否</option>
                                        <option value="1">是</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label>是否农村特困</label>
                                    <select required type="date" class="form-control" name="profile[very_poor]">
                                        <option value="0">否</option>
                                        <option value="1">是</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label>是否残疾</label>
                                    <select required type="date" class="form-control" name="profile[disability]">
                                        <option value="0">否</option>
                                        <option value="1">是</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h4>联系人/联系方式</h4>
                        <hr>

                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label>家长姓名</label>
                                    <input required type="text" class="form-control" value="" placeholder="家长姓名" name="profile[parent_name]">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>家长电话</label>
                                    <input required type="text" class="form-control" value="" placeholder="家长电话" name="profile[parent_mobile]">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>QQ 号码</label>
                                    <input type="text" class="form-control" value="" placeholder="选填: QQ号码" name="profile[qq]">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label>家庭住址</label>
                                    <input type="text" class="form-control" value="" placeholder="选填: 家庭住址" name="profile[source_place]">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>政治面貌</label>
                                    <input required type="text" class="form-control" value="" placeholder="政治面貌" name="profile[political_name]">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>民族</label>
                                    <input required type="text" class="form-control" value="" placeholder="民族" name="profile[nation_name]">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>备注</label>
                            <textarea class="form-control" placeholder="选填: 备注" name="profile[comments]"></textarea>
                        </div>
                        <div id="app-init-data-holder" data-school="{{ session('school.id') }}"></div>
                        <?php
                        Button::Print(['id'=>'btn-create-building','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                        ?>&nbsp;
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
