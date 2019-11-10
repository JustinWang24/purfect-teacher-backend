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
                <b>籍贯</b> <a class="pull-right">{{ $profile->source_place }}</a>
            </li>
            <li class="list-group-item">
                <b>中高考分数</b> <a class="pull-right">{{ $profile->examination_score }}</a>
            </li>
            <li class="list-group-item">
                <b>入学年份</b> <a class="pull-right">{{ $profile->year }}年</a>
            </li>
            <li class="list-group-item">
                <b>生日/年龄</b> <a class="pull-right">{{ _printDate($profile->birthday) }}({{ Carbon\Carbon::now()->diffInYears($profile->birthday) }}岁)</a>
            </li>
        </ul>
        <!-- END SIDEBAR USER TITLE -->
        <!-- SIDEBAR BUTTONS -->
        <div class="profile-userbuttons">
            @if(isset($registration))
                <button type="button"
                        class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 btn-circle btn-primary">
                    状态: {{ $registration->getStatusText() }}
                </button>
                <button type="button"
                        class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 btn-circle btn-{{ $registration->isRelocationAllowed()?'success':'danger' }}">
                    {{ $registration->isRelocationAllowed() ? '服从分配' : '不服从分配' }}
                </button>
            @endif
        </div>
        <!-- END SIDEBAR BUTTONS -->
    </div>
</div>