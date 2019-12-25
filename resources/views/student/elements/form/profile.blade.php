<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>
<form action="{{ route('verified_student.profile.update') }}" method="post" id="edit-student-form">
    @csrf
    <input type="hidden" name="user[id]" value="{{ $student->id }}">
    <input type="hidden" name="user[uuid]" value="{{ $student->uuid }}">
    <h3 class="text-info mt-4">学生基本信息(报名时填写)</h3>
    <hr>
    <div class="row">
        <div class="col-md-4 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="student-number-input">学号(录取编号)</label>
                <input required disabled type="text" class="form-control" id="student-number-input" value="{{ isset($profile) ? $profile->student_number : $student->profile->student_number }}" placeholder="学号(录取编号)" name="profile[student_number]">
            </div>
        </div>
        <div class="col-md-4 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="student-name-input">姓名(必填)</label>
                <input required type="text" class="form-control" id="student-name-input" value="{{ $student->name }}" placeholder="姓名" name="user[name]">
            </div>
        </div>
        <div class="col-md-4 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="student-mobile-input">手机号码(必填)</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-mobile"></i></span>
                    <input required type="text" class="form-control" id="student-mobile-input" value="{{ $student->mobile }}" placeholder="手机号码" name="user[mobile]">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="student-id-number-input">学生身份证号(必填)</label>
                <input required type="text" class="form-control" id="student-id-number-input" value="{{ $profile->id_number??$student->profile->id_number }}" placeholder="学生身份证号" name="profile[id_number]">
            </div>
        </div>
        <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="student-license_number-input">准考证号(选填)</label>
                <input type="text" class="form-control" id="student-license_number-input" value="{{ isset($profile) ? $profile->license_number : $student->profile->license_number }}" placeholder="准考证号(选填)" name="profile[license_number]">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="student-political_name-input">政治面貌</label>
                <select class="form-control" id="student-political_name-input" name="profile[political_name]">
                    <option value="团员" {{ $profile->political_name??$student->profile->political_name === '团员' ? 'selected' : null }}>团员</option>
                    <option value="党员" {{ $profile->political_name??$student->profile->political_name === '党员' ? 'selected' : null }}>党员</option>
                    <option value="群众" {{ $profile->political_name??$student->profile->political_name === '群众' ? 'selected' : null }}>群众</option>
                </select>
            </div>
        </div>
        <div class="col-md-4 col-sm-12 col-xs-12">
            <label for="student-id-number-input">性别</label>
            <select class="form-control" id="profile-gender-input" name="profile[gender]">
                <option value="{{ \App\User::GENDER_MALE }}" {{ (isset($profile) ? $profile->gender : $student->profile->gender) === \App\User::GENDER_MALE ? 'selected' : null }}>男</option>
                <option value="{{ \App\User::GENDER_FEMALE }}" {{ (isset($profile) ? $profile->gender : $student->profile->gender) === \App\User::GENDER_FEMALE ? 'selected' : null }}>女</option>
            </select>
        </div>
        <div class="col-md-4 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="student-nation_name-input">民族(必填)</label>
                <input required type="text" class="form-control" id="student-nation_name-input" value="{{ $profile->nation_name??$student->profile->nation_name }}" placeholder="民族" name="profile[nation_name]">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="student-political_name-input">招生年度</label>
                <select class="form-control" id="student-year-input" name="profile[year]">
                    @php
                        $nextYear = intval(date('Y')) + 1;
                        $years = array_reverse(range($nextYear-10, $nextYear));
                    @endphp
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ ($profile->year??$student->profile->year) === $year ? 'selected' : null }}>{{ $year }}年</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
                <?php
                $birthday = null;
                if(isset($profile)){
                    $birthday = $profile->birthday;
                }elseif (isset($student)){
                    $birthday = $student->profile->birthday;
                }
                ?>
                @if($birthday)
                <label for="student-political_name-input">出生日期(系统自动维护, 与身份证信息同步)</label><br>
                <select style="width: 100px;" class="form-control float-md-left mr-2" id="student-year-input" disabled>
                    @php
                        $firstYear = intval(date('Y')) - 50;
                        $years = range($firstYear, $firstYear + 18);
                    @endphp
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ ($profile->birthday->year??$student->profile->birthday->year) === $year ? 'selected' : null }}>{{ $year }}年</option>
                    @endforeach
                </select>
                <select style="width: 100px;" class="form-control float-md-left mr-2" id="student-year-input" disabled>
                    @foreach(range(1,12) as $month)
                        <option value="{{ $month }}" {{ ($profile->birthday->month??$student->profile->birthday->month) === $month ? 'selected' : null }}>{{ $month }}月</option>
                    @endforeach
                </select>
                <select style="width: 100px;" class="form-control float-md-left" id="student-year-input" disabled>
                    @foreach(range(1,31) as $day)
                        <option value="{{ $day }}" {{ ($profile->birthday->day??$student->profile->birthday->day) === $day ? 'selected' : null }}>{{ $day }}日</option>
                    @endforeach
                </select>
                @else
                        <label for="student-political_name-input">没有该学生生日的数据</label>
                @endif
            </div>
        </div>
        <div class="col-md-3 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="student-country-input">籍贯(必填)</label>
                <input required type="text" class="form-control" id="student-country-input" value="{{ $profile->source_place??$student->profile->source_place }}" placeholder="籍贯: 北京市" name="profile[source_place]">
            </div>
        </div>
    </div>

    <h3 class="text-info">联系方式</h3>
    <hr>
    <div class="row">
        <div class="col-md-4 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="student-email-input">QQ号(选填)</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-qq"></i></span>
                    <input type="text" class="form-control" id="student-qq-input" value="{{ $profile->qq??$student->profile->qq }}" placeholder="QQ号: 选填" name="profile[qq]">
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="student-email-input">微信(选填)</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-weixin"></i></span>
                    <input type="text" class="form-control" id="student-wechat-input" value="{{ $profile->wx??$student->profile->wx }}" placeholder="微信: 选填" name="profile[wx]">
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="student-email-input">邮件(选填)</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                    <input type="text" class="form-control" id="student-email-input" value="{{ $student->email }}" placeholder="电子邮件: 选填" name="user[email]">
                </div>
            </div>
        </div>
    </div>

    <h3 class="text-info">家长信息</h3>
    <hr>
    <div class="row">
        <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="student-parent_name-input">家长姓名(必填)</label>
                <input required type="text" class="form-control" id="student-parent_name-input" value="{{ $profile->parent_name??$student->profile->parent_name }}" placeholder="家长姓名: 必填" name="profile[parent_name]">
            </div>
        </div>
        <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="student-email-input">家长电话(必填)</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-mobile"></i></span>
                    <input required type="text" class="form-control" id="student-wechat-input" value="{{ $profile->parent_mobile??$student->profile->parent_mobile }}" placeholder="家长电话: 必填" name="profile[parent_mobile]">
                </div>
            </div>
        </div>
    </div>

    <h3 class="text-info">申报信息</h3>
    <hr>
    <div class="row">
        <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="student-test_scores-input">中/高考分数(选填)</label>
                <input type="text" class="form-control" id="student-test_scores-input" value="{{ $profile->examination_score??$student->profile->examination_score }}" placeholder="中/高考分数(选填)" name="profile[examination_score]">
            </div>
        </div>
        <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="student-email-input">是否服从调剂</label>
                <select class="form-control" id="profile-gender-input" name="profile[relocation_allowed]">
                    <option value="0" {{ (isset($profile) ? $profile->relocation_allowed : $student->profile->relocation_allowed) ? null :'selected' }}>不服从</option>
                    <option value="1" {{ (isset($profile) ? $profile->relocation_allowed : $student->profile->relocation_allowed) ? 'selected' : null }}>服从调剂</option>
                </select>
            </div>
        </div>
    </div>

    <?php
    Button::Print(['id'=>'btn-create-campus','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
    ?>&nbsp;
</form>
