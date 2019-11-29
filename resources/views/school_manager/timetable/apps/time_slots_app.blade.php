@php
    use App\Utils\UI\Anchor;
    use App\Utils\UI\Button;
    use App\User;
    use App\Utils\Misc\ConfigurationTool;
    /**
     * @var \App\Models\Schools\SchoolConfiguration $config
     */
    $ecFrom1 = $config->getElectiveCourseAvailableFrom(1); // 第一学期选修课开始选课的时间
    $ecTo1= $config->getElectiveCourseAvailableTo(1); // 第一学期选修课结束选课的时间
    $ecFrom2 = $config->getElectiveCourseAvailableFrom(2); // 第2学期选修课开始选课的时间
    $ecTo2= $config->getElectiveCourseAvailableTo(2); // 第2学期选修课结束选课的时间
    $term1Start= $config->getTermStartDate(1); // 第1学期的开学时间
    $term2Start= $config->getTermStartDate(2); // 第2学期开学时间
@endphp

<div class="row" id="school-time-slots-manager">
    <div class="col-4">
        <div class="card">
            <div class="card-head">
                <header>{{ session('school.name') }} 日期配置</header>
            </div>
            <div class="card-body">
                <form action="{{ route('school_manager.school.config.update') }}" method="post"  id="edit-school-config-form">
                    @csrf
                    <input type="hidden" id="school-config-id-input" name="uuid" value="{{ session('school.uuid') }}">
                    <input type="hidden" name="redirectTo" value="{{ route('school_manager.timetable.manager',['uuid'=>session('school.uuid')])  }}">
                    <div class="form-group">
                        <label>第一学期, 学生可以在以下时间段选择选修课</label>
                        <div class="clearfix"></div>
                        @php
                            $months = range(1,12);
                            $days = range(1,31);
                        @endphp
                        <select class="form-control pull-left mr-2" name="ec1[from][month]" style="width: 20%;">
                            @foreach($months as $month)
                                <option value="{{ $month }}" {{ $month===$ecFrom1->month ? 'selected':null }}>{{ $month }}月</option>
                            @endforeach
                        </select>
                        <select class="form-control pull-left" name="ec1[from][day]" style="width: 20%;">
                            @foreach($days as $day)
                                <option value="{{ $day }}" {{ $ecFrom1->day === $day ? 'selected':null }}>{{ $day }}日</option>
                            @endforeach
                        </select>
                        <p class="pull-left m-2"> - </p>
                        <select class="form-control pull-left mr-2" name="ec1[to][month]" style="width: 20%;">
                            @foreach($months as $month)
                                <option value="{{ $month }}" {{ $month===$ecTo1->month ? 'selected':null }}>{{ $month }}月</option>
                            @endforeach
                        </select>
                        <select class="form-control pull-left" name="ec1[to][day]" style="width: 20%;">
                            @foreach($days as $day)
                                <option value="{{ $day }}" {{ $ecTo1->day === $day ? 'selected':null }}>{{ $day }}日</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="clearfix"></div>
                    <div class="form-group mt-4">
                        <label>第二学期, 学生可以在一下时间段选择选修课</label>
                        <div class="clearfix"></div>
                        <select class="form-control pull-left mr-2" name="ec2[from][month]" style="width: 20%;">
                            @foreach($months as $month)
                                <option value="{{ $month }}" {{ $month===$ecFrom2->month ? 'selected':null }}>{{ $month }}月</option>
                            @endforeach
                        </select>
                        <select class="form-control pull-left" name="ec2[from][day]" style="width: 20%;">
                            @foreach($days as $day)
                                <option value="{{ $day }}" {{ $ecFrom2->day === $day ? 'selected':null }}>{{ $day }}日</option>
                            @endforeach
                        </select>
                        <p class="pull-left m-2"> - </p>
                        <select class="form-control pull-left mr-2" name="ec2[to][month]" style="width: 20%;">
                            @foreach($months as $month)
                                <option value="{{ $month }}" {{ $month===$ecTo2->month ? 'selected':null }}>{{ $month }}月</option>
                            @endforeach
                        </select>
                        <select class="form-control pull-left" name="ec2[to][day]" style="width: 20%;">
                            @foreach($days as $day)
                                <option value="{{ $day }}" {{ $ecTo2->day === $day ? 'selected':null }}>{{ $day }}日</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="clearfix"></div>
                    <hr>

                    <div class="form-group mt-4">
                        <label>本学年第一学期开学时间</label>
                        <div class="clearfix"></div>
                        <select class="form-control pull-left mr-2" name="term_start[term1][month]" style="width: 20%;">
                            @foreach($months as $month)
                                <option value="{{ $month }}" {{ $month===$term1Start->month ? 'selected':null }}>{{ $month }}月</option>
                            @endforeach
                        </select>
                        <select class="form-control pull-left" name="term_start[term1][day]" style="width: 20%;">
                            @foreach($days as $day)
                                <option value="{{ $day }}" {{ $term1Start->day === $day ? 'selected':null }}>{{ $day }}日</option>
                            @endforeach
                        </select>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group mt-4">
                        <label>本学年第二学期开学时间</label>
                        <div class="clearfix"></div>
                        <select class="form-control pull-left mr-2" name="term_start[term2][month]" style="width: 20%;">
                            @foreach($months as $month)
                                <option value="{{ $month }}" {{ $month===$term2Start->month ? 'selected':null }}>{{ $month }}月</option>
                            @endforeach
                        </select>
                        <select class="form-control pull-left" name="term_start[term2][day]" style="width: 20%;">
                            @foreach($days as $day)
                                <option value="{{ $day }}" {{ $term2Start->day === $day ? 'selected':null }}>{{ $day }}日</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="clearfix"></div>
                    <hr>
                    <?php
                    Button::Print(['id'=>'btn-save-school-config','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                    ?>&nbsp;
                </form>
            </div>
        </div>
    </div>
    <div class="col-3">
        <div class="card">
            <time-slots-manager
                    school="{{ $school->uuid }}"
                    v-on:edit-time-slot="editTimeSlotHandler"
            ></time-slots-manager>
        </div>
    </div>
    <div class="col-5" v-if="showEditForm">
        <div class="card">
            <div class="card-head">
                <header>作息时间表</header>
            </div>
            <div class="card-body">
                <el-form ref="form" :model="currentTimeSlot" label-width="80px">
                    <el-form-item label="名称">
                        <el-input v-model="currentTimeSlot.name"></el-input>
                    </el-form-item>
                    <el-form-item label="时间段">
                        <el-time-picker
                                v-model="currentTimeSlot.from"
                                value-format="HH:mm:ss"
                                :picker-options="{selectableRange: '05:00:00 - 22:00:00', format: 'HH:mm:ss'}"
                                placeholder="起始时间">
                        </el-time-picker>
                        <el-time-picker
                                class="mt-4"
                                arrow-control
                                v-model="currentTimeSlot.to"
                                value-format="HH:mm:ss"
                                :picker-options="{selectableRange: '05:00:00 - 22:00:00', format: 'HH:mm:ss'}"
                                @change="toChangedHandler"
                                placeholder="结束时间">
                        </el-time-picker>
                    </el-form-item>
                    <el-form-item label="类型">
                        <el-select v-model="currentTimeSlot.type" placeholder="请选择">
                            @foreach(\App\Models\Timetable\TimeSlot::AllTypes() as $key=>$value)
                            <el-option
                                    :key="{{ $key }}"
                                    label="{{ $value }}"
                                    :value="{{ $key }}">
                            </el-option>
                            @endforeach
                        </el-select>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="onSubmit">保存</el-button>
                        <el-button @click="showEditForm = false">关闭</el-button>
                    </el-form-item>
                </el-form>
            </div>
        </div>
    </div>
</div>