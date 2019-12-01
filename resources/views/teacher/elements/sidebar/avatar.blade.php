<div class="card card-topline-aqua">
    <div class="card-body no-padding height-9">
        <div class="row">
            <div class="profile-userpic">
                <img src="{{ $profile->avatar }}" class="img-responsive" alt="">
            </div>
        </div>
        <div class="profile-usertitle">
            <div class="profile-usertitle-name">{{ $profile->user->name }}</div>
        </div>
        <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
                <b>教师编号</b> <a class="pull-right">{{ $profile->serial_number }}</a>
            </li>
            <li class="list-group-item">
                <b>职称</b> <a class="pull-right">{{ $profile->title }}</a>
            </li>
            <li class="list-group-item">
                <b>性别</b> <a class="pull-right">{{ $profile->gender === \App\User::GENDER_MALE ? '男' : '女' }}</a>
            </li>
            <li class="list-group-item">
                <b>生日/年龄</b> <a class="pull-right">{{ _printDate($profile->birthday) }}({{ Carbon\Carbon::now()->diffInYears($profile->birthday) }}岁)</a>
            </li>

            <li class="list-group-item">
                <b>政治面貌</b> <a class="pull-right">{{ $profile->political_name }}</a>
            </li>
            <li class="list-group-item">
                <b>民族</b> <a class="pull-right">{{ $profile->nation_name }}</a>
            </li>
            <li class="list-group-item">
                <b>最高学历</b> <a class="pull-right">{{ $profile->education }}</a>
            </li>
            <li class="list-group-item">
                <b>学位</b> <a class="pull-right">{{ $profile->degree }}</a>
            </li>
            <li class="list-group-item">
                <b>所在部门</b> <a class="pull-right">{{ $profile->group_name }}</a>
            </li>
        </ul>
    </div>
</div>