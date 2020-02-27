<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>
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
                        <span class="pull-left" style="padding-top: 6px;">缴费信息</span>
                    </header>
                </div>
                <div class="card-body menu-blck">

                    <!--学费-->
                    @if( $dataOne['typeid'] == 1)
                        @if(empty($dataOne['payInfo']))
                        <form action="{{ route('welcome_manager.welcomeReport.cost_detail',['id'=>Request::get('id'),'typeid'=>Request::get('typeid'),'index'=>Request::get('index')]) }}" method="post"  id="add-building-form">
                            @csrf
                            <div class="form-group">
                                <label for="school-name-input">缴费金额</label>
                                <input required type="text" class="form-control" name="infos[pay_price]" placeholder="例如：5000">
                            </div>
                            <div class="form-group">
                                <label for="school-name-input">支付方式</label>
                                <select id="pay_payment" class="form-control" name="infos[pay_payment]"  required>
                                    <option value="">-请选择-</option>
                                    <option value="1" @if( old('pay_payment') == 1 ) selected @endif >微信</option>
                                    <option value="2" @if( old('pay_payment') == 2 ) selected @endif >支付宝</option>
                                    <option value="3" @if( old('pay_payment') == 3 ) selected @endif >其他</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="school-name-input">流水号</label>
                                <input required type="text" class="form-control" name="infos[pay_trade_sn]" placeholder="例如：100100100100100100">
                            </div>
                            <div class="form-group">
                                <label for="school-name-input">备注信息</label>
                                <textarea  class="form-control" name="infos[project_desc]" cols="10" rows="10" placeholder="" style="resize:none;">{{ old('project_desc') }}</textarea>
                            </div>
                            <div class="but-foter">
                            <?php
                            Button::Print(['id'=>'btn-create-building','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                            ?>
                            <?php
                            Anchor::Print(['text'=>trans('general.return'),'href'=>route('welcome_manager.welcomeReport.tuitionfee_list',['uuid'=>session('school')['uuid'],'index'=>Request::get('index')]),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                            ?>
                            </div>
                        </form>
                        @else
                            <div>
                                <el-form label-width="100px">
                                    <el-row>
                                        <el-form-item label="名称：">
                                            <el-col>{{ $dataOne['payInfo']['project_title'] }}</el-col>
                                        </el-form-item>
                                    </el-row>
                                    <el-row>
                                        <el-form-item label="订单编号：">
                                            <el-col>{{ $dataOne['payInfo']['pay_trade_sn'] }}</el-col>
                                        </el-form-item>
                                    </el-row>
                                    <el-row>
                                        <el-form-item label="支付金额：">
                                            <el-col>{{ $dataOne['payInfo']['pay_price'] }}</el-col>
                                        </el-form-item>
                                    </el-row>
                                    <el-row>
                                        <el-form-item label="支付方式：">
                                            <el-col>{{ $paymentArr[$dataOne['payInfo']['pay_payment']] }}</el-col>
                                        </el-form-item>
                                    </el-row>
                                    <el-row>
                                        <el-form-item label="操作员：">
                                            <el-col>{{ $dataOne['payInfo']['operator_name'] }}</el-col>
                                        </el-form-item>
                                    </el-row>
                                    <el-row>
                                        <el-form-item label="操作时间：">
                                            <el-col>{{ $dataOne['payInfo']['operator_datetime'] }}</el-col>
                                        </el-form-item>
                                    </el-row>
                                    <el-row>
                                        <el-form-item label="备注信息：">
                                            <el-col>{{ $dataOne['payInfo']['project_desc'] }}</el-col>
                                        </el-form-item>
                                    </el-row>
                                </el-form>
                                <?php
                                Anchor::Print(['text'=>trans('general.return'),'href'=>route('welcome_manager.welcomeReport.tuitionfee_list',['uuid'=>session('school')['uuid'],'index'=>Request::get('index')]),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                                ?>
                            </div>
                        @endif
                    @endif

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
    .pics{
        height: 120px;
        margin-bottom: 30px;
    }
    .pics-1{
        float: left;
        width: 170px;
        height: 110px;
        margin: 10px;
        text-align: center;
    }
    .pics-1 img{
        width: 100%;
        height: 100%;
    }
    .checkbox-1 p{
        width: 150px;
        height: 35px;
        float: left;
        font-size: 20px;
    }
    .notice-1 {
        clear: both;
        color: red;
        font-size: 20px;
    }
    .but-foter{
        margin-top: 88px;
    }
</style>
