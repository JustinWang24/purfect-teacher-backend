@extends('layouts.app')
@section('content')
    <div class="row" id="school-welcome-list-app">

        <div class="col-5">
            <div class="card">
                <div class="card-head">
                    <header class="full-width">
                        <span class="pull-left" style="padding-top: 6px;">详细信息</span>
                    </header>
                </div>
                <div class="card-body menu-blck">
                        <!--列表展示-->
                        <div>
                            <el-form label-width="100px">
                                <el-row>
                                    <el-col span="12">
                                        <el-form-item label="学院：">
                                            <el-col>{{ $dataOne['info1']['institute_name'] }}</el-col>
                                        </el-form-item>
                                    </el-col>
                                    <el-col span="12">
                                        <el-form-item label="专业：">
                                            <el-col>{{ $dataOne['info1']['major_name'] }}</el-col>
                                        </el-form-item>
                                    </el-col>
                                </el-row>
                                <el-row>
                                    <el-col span="12">
                                        <el-form-item label="姓名：">
                                            <el-col>{{ $dataOne['info1']['user_name']?$dataOne['info1']['user_name']:'---' }}</el-col>
                                        </el-form-item>
                                    </el-col>
                                    <el-col span="12">
                                        <el-form-item label="性别：">
                                            <el-col>{{ $dataOne['info1']['gender']?$dataOne['info1']['gender']:'---' }}</el-col>
                                        </el-form-item>
                                    </el-col>
                                </el-row>
                                <el-row>
                                    <el-col span="12">
                                        <el-form-item label="民族：">
                                            <el-col>{{ $dataOne['info1']['nation_name']?$dataOne['info1']['nation_name']:'---' }}</el-col>
                                        </el-form-item>
                                    </el-col>
                                    <el-col span="12">
                                        <el-form-item label="身份证号：">
                                            <el-col>{{ $dataOne['info1']['id_number']?$dataOne['info1']['id_number']:'---' }}</el-col>
                                        </el-form-item>
                                    </el-col>
                                </el-row>
                                <el-row>
                                    <el-col span="12">
                                        <el-form-item label="出身日期：">
                                            <el-col>{{ $dataOne['info1']['birthday']?$dataOne['info1']['birthday']:'---' }}</el-col>
                                        </el-form-item>
                                    </el-col>
                                    <el-col span="12">
                                        <el-form-item label="政治面貌：">
                                            <el-col>{{ $dataOne['info1']['political_name']?$dataOne['info1']['political_name']:'---' }}</el-col>
                                        </el-form-item>
                                    </el-col>
                                </el-row>
                                <el-row>
                                    <el-col span="12">
                                        <el-form-item label="联系电话：">
                                            <el-col>{{ $dataOne['info1']['telephone']?$dataOne['info1']['telephone']:'---' }}</el-col>
                                        </el-form-item>
                                    </el-col>
                                    <el-col span="12">
                                        <el-form-item label="电子邮箱：">
                                            <el-col>{{ $dataOne['info1']['email']?$dataOne['info1']['email']:'---' }}</el-col>
                                        </el-form-item>
                                    </el-col>
                                </el-row>
                                <el-row>
                                    <el-col span="12">
                                        <el-form-item label="QQ号码：">
                                            <el-col>{{ $dataOne['info1']['qq']?$dataOne['info1']['qq']:'---' }}</el-col>
                                        </el-form-item>
                                    </el-col>
                                    <el-col span="12">
                                        <el-form-item label="微信号：">
                                            <el-col>{{ $dataOne['info1']['wx']?$dataOne['info1']['wx']:'---' }}</el-col>
                                        </el-form-item>
                                    </el-col>
                                </el-row>
                                <el-row>
                                    <el-col span="12">
                                        <el-form-item label="家长姓名：">
                                            <el-col>{{ $dataOne['info1']['parent_name']?$dataOne['info1']['parent_name']:'---' }}</el-col>
                                        </el-form-item>
                                    </el-col>
                                    <el-col span="12">
                                        <el-form-item label="家长电话：">
                                            <el-col>{{ $dataOne['info1']['parent_mobile']?$dataOne['info1']['parent_mobile']:'---' }}</el-col>
                                        </el-form-item>
                                    </el-col>
                                </el-row>
                                <el-row>
                                    <el-col span="12">
                                        <el-form-item label="籍贯地：">
                                            <el-col>
                                                {{ $dataOne['info1']['place_province_name']}}
                                                {{ $dataOne['info1']['place_city_name']}}
                                                {{ $dataOne['info1']['place_area_nme']}}
                                            </el-col>
                                        </el-form-item>
                                    </el-col>
                                    <el-col span="12">
                                        <el-form-item label="生源地：">
                                            <el-col>
                                                {{ $dataOne['info1']['source_province_name']}}
                                                {{ $dataOne['info1']['source_city_name']}}
                                                {{ $dataOne['info1']['source_area_nme']}}
                                            </el-col>
                                        </el-form-item>
                                    </el-col>
                                </el-row>
                                <el-row>
                                    <el-col>
                                        <el-form-item label="家庭地址：">
                                            <el-col>
                                                {{ $dataOne['info1']['family_province_name']}}
                                                {{ $dataOne['info1']['family_city_name']}}
                                                {{ $dataOne['info1']['family_area_nme']}}
                                                {{ $dataOne['info1']['family_detailed_address']}}
                                            </el-col>
                                        </el-form-item>
                                    </el-col>
                                </el-row>
                                <!--el-row>
                                    <el-form-item>
                                        <el-button type="primary">保存</el-button>
                                        <el-button>重置</el-button>
                                    </el-form-item>
                                </el-row-->
                            </el-form>
                        </div>
                </div>
            </div>
        </div>

        <div class="col-7">
            <div class="card">
                <div class="card-head">
                    <header class="full-width">
                        <span class="pull-left" style="padding-top: 6px;">详细信息</span>
                    </header>
                </div>
                <div class="card-body menu-blck">
                    <!--列表展示-->
                    <div>
                        <el-form label-width="100px">
                            <el-row>
                                <el-form-item label="状态：">
                                    <el-col>{{ $reportStatusArr[$dataOne['status']] }}</el-col>
                                </el-form-item>
                            </el-row>
                            <el-row>
                                <el-form-item label="报到时间：">
                                    <el-col>{{ $dataOne['complete_date']?$dataOne['complete_date']:'---' }}</el-col>
                                </el-form-item>
                            </el-row>
                        </el-form>
                    </div>
                    <div class="ln-title"><span>照片信息</span></div>
                    <div>
                        <el-form label-width="100px">
                            <div class="image">
                                @foreach($dataOne['info2'] as $key=>$val)
                                    <div class="pics-block">
                                        <a href="{{ $val }}" target="_blank"><img src="{{ $val }}"></a>
                                        <span class="title">{{ $reportPicsArr[$key] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </el-form>
                    </div>

                    <div class="card-head" style="margin-top: 10px;">
                        <header class="full-width">
                            <span>照片信息</span>
                        </header>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<style>
    *{
        margin: 0;
        padding: 0;
    }
    .el-form-item__label {
        font-weight: bold;
    }
    .menu-blck{
        height: 700px;
    }
    .ln-title{
        margin-top: 5px;
        margin-bottom: 5px;
        border-bottom: #a6b4cd 1px solid;
    }
    .ln-title span{
        font-size: 18px;
        font-weight: bold;
    }
    .pics-block{
        float: left;
        width: 170px;
        height: 110px;
        margin: 10px;
        text-align: center;
    }
    .pics-block img{
        width: 100%;
        height: 100%;
    }
    .
</style>
