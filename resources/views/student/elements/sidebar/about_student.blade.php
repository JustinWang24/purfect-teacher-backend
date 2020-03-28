<div class="card">
    <div class="card-head card-topline-aqua">
        <header>个人资料</header>
    </div>
    <div class="card-body no-padding height-9">
        <div class="profile-desc">

        </div>
        <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
                <b>性别 </b>
                <div class="profile-desc-item pull-right">{{ $profile->gender === \App\User::GENDER_MALE ? '男':'女' }}</div>
            </li>
            @if(isset($plan))
            <li class="list-group-item">
                <b>报名专业: {{ $plan->major_name }}</b>
            </li>
            @endif
            <li class="list-group-item">
                <b>电子邮件: <a href="mailto:{{ $profile->user->email }}">{{ $profile->user->email }}</a> </b>
            </li>
            <li class="list-group-item">
                <b>电话: {{ $profile->user->mobile }}</b>
            </li>
            @if($profile->wx)
                <li class="list-group-item">
                    <b>微信号: {{ $profile->wx }}</b>
                </li>
            @endif
            @if($profile->qq)
            <li class="list-group-item">
                <b>QQ号: {{ $profile->qq }}</b>
            </li>
            @endif
        </ul>
        @if($is_show == 1)
            <div class="row list-separated profile-stat">
                <div class="col-md-4 col-sm-4 col-6">
                    <div class="uppercase profile-stat-title"> {{$student->gradeUser->grade->studentsCount()}} </div>
                    <div class="uppercase profile-stat-text"> 同学 </div>
                </div>
                <div class="col-md-4 col-sm-4 col-6">
                    <div class="uppercase profile-stat-title"> {{$student->gradeUser->grade->major->courseCount() }}</div>
                    <div class="uppercase profile-stat-text"> 专业课 </div>
                </div>
                <div class="col-md-4 col-sm-4 col-6">
                    <div class="uppercase profile-stat-title"> 0 </div>
                    <div class="uppercase profile-stat-text"> 作业 </div>
                </div>
            </div>
        @endif
    </div>
</div>
