<div class="row" id="school-time-slots-manager">
    <div class="col-4">
        <div class="card">
            <time-slots-manager
                    school="{{ $school->uuid }}"
                    v-on:edit-time-slot="editTimeSlotHandler"
            ></time-slots-manager>
        </div>
    </div>
    <div class="col-8" v-if="showEditForm">
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
                                arrow-control
                                v-model="currentTimeSlot.to"
                                value-format="HH:mm:ss"
                                :picker-options="{selectableRange: '05:00:00 - 22:00:00', format: 'HH:mm:ss'}"
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