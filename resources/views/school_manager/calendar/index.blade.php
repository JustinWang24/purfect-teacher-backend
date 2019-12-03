@php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
@endphp

@extends('layouts.app')
@section('content')
    <div class="row" id="school-calendar-app">
        <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
            <div class="card">
                <div class="card-head">
                    <header>
                        添加校历事件
                    </header>
                </div>
                <div class="card-body">
                    <el-form ref="form" :model="form" label-width="80px">
                        <label for="">活动日期</label>
                        <el-input v-model="form.event_time"></el-input>
                        <br>
                        <br>
                        <label for="">事件标签</label><br>
                        <el-select
                                v-model="form.tag"
                                style="width: 100%;"
                                multiple
                                filterable
                                allow-create
                                default-first-option
                                placeholder="请选择文章标签">
                            <el-option
                                    v-for="(item, id) in tags"
                                    :key="id"
                                    :label="item"
                                    :value="item">
                            </el-option>
                        </el-select>
                        <br>
                        <br>
                        <label for="">时间内容</label>
                        <br>
                        <el-input type="textarea" v-model="form.content"></el-input>
                        <br>
                        <br>
                        <el-form-item>
                            <el-button type="primary" @click="onSubmit">保 存</el-button>
                            <el-button >取 消</el-button>
                        </el-form-item>
                    </el-form>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-9 col-lg-9 col-xl-9">
            <div class="card">
                <div class="card-head">
                    <header>
本学期共: <span class="text-primary">{{ $config->study_weeks_per_term }}周</span>, 开学日期: <span class="text-primary">{{ _printDate($config->getTermStartDate()) }}</span>
                    </header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <el-calendar v-on:input="dateClicked">
                            <!-- 这里使用的是 2.5 slot 语法，对于新项目请使用 2.6 slot 语法-->
                            <template
                                    slot="dateCell"
                                    slot-scope="{date, data}">
                                <p :class="data.isSelected ? 'is-selected' : ''" style="margin: 0;">
                                    @{{ data.day.split('-').slice(1).join('月') }}日 @{{ data.isSelected ? '✔️' : ''}}
                                </p>
                                <div v-if="getEvent(data.day)">
                                    <el-tag
                                            v-for="(t, idx) in getEvent(data.day).tag"
                                            :key="idx" size="mini">
                                        @{{ t }}
                                    </el-tag>
                                </div>
                            </template>
                        </el-calendar>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="app-init-data-holder"
         data-school="{{ session('school.id') }}"
         data-events='{!! $events->toJson() !!}'
         data-tags='{!! json_encode($tags) !!}'
    ></div>
@endsection
