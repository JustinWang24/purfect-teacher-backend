<div class="card">
    <div class="card-topline-aqua">
        <header></header>
    </div>
    <div class="white-box">
        <!-- Nav tabs -->
        <div class="p-rl-20">
            <ul class="nav customtab nav-tabs" role="tablist">
                <li class="nav-item"><a href="#tab1" class="nav-link active"
                                        data-toggle="tab">招生报名表</a></li>
                <li class="nav-item"><a href="#tab2" class="nav-link"
                                        data-toggle="tab">{{ $registration->getStatusText() }}</a></li>
            </ul>
        </div>
        <div class="tab-content">
            @include('student.elements.form.profile',['student'=>$student,'profile'=>$registration->profile])
        </div>
    </div>
</div>