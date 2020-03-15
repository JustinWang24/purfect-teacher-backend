@php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
@endphp
@extends('layouts.app')
@section('content')
    <div class="row" id="banner-manager-app">

        <div class="col-sm-12 col-md-12">
            <div class="card">
                <div class="card-head">
                  <form name="search" action="{{ route('school_manager.banner.list') }}" method="get"  id="add-building-form">
                    <header class="full-width">
                      <div class="pull-left col-3">
                        <label>应用</label>
                        <select id="app" class="el-input__inner col-8" name="app">
                          <option value="">-请选择-</option>
                          <option value="1" @if( Request::get('app') == 1 ) selected @endif >学生端</option>
                          <option value="2" @if( Request::get('app') == 2 ) selected @endif >教师端</option>
                        </select>
                      </div>
                      <div class="pull-left col-3">
                        <label>状态</label>
                        <select id="status" class="el-input__inner col-8" name="status">
                          <option value="" @if( Request::get('status') == "" ) selected @endif>-请选择-</option>
                          <option value="0" @if( is_numeric(Request::get('status')) && Request::get('status') == 0 ) selected @endif >已关闭</option>
                          <option value="1" @if( Request::get('status') == 1 ) selected @endif >已开启</option>
                        </select>
                      </div>
                      <el-button class="pull-right" type="primary" @click="createNewBanner()" >添加</el-button>
                    </header>
                  </form>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <tr>
                                    <th>ID</th>
                                    <th>排序</th>
                                    <th>应用</th>
                                    <th>位置</th>
                                    <th>类型</th>
                                    <th>标题</th>
                                    <th style="width: 150px;">图片</th>
                                    <th>浏览</th>
                                    <th>状态</th>
                                    <th>添加时间</th>
                                    <th>操作</th>
                                </tr>
                                <tbody>
                                @foreach($data as $val)
                                    <tr>
                                        <td>{{ $val->id }}</td>
                                        <td>
                                          <input type="text" class="input-text-c input-text" id="sort_{{ $val->id }}" onblur="sort({{ $val->id }},this.value)"
                                                 value="{{ $val->sort }}" style="width:100px;height:30px;" name="listorders[{{ $val->id }}]">
                                        </td>
                                        <td>{{ $val->getAppStr($val->app) }}</td>
                                        <td>{{ $val->getPositStr($val->posit) }}</td>
                                        <td>{{ $val->getTypeStr($val->type) }}</td>
                                        <td>{{ $val->title }}</td>
                                        <td>
                                          <a href="{{ $val->image_url }}" target="_blank">
                                            <img src="{{ $val->image_url }}" width="120px" height="120px" alt="">
                                          </a>
                                        </td>
                                      <td>
                                        @if($val['public'] == 1)<span>已登录</span>@else<span>未登录</span>@endif
                                      </td>
                                      <td>
                                          @if($val['status'] == 1)<span>已开启</span>@else<span>已关闭</span>@endif
                                      </td>
                                      <td>{{ $val->created_at }}</td>
                                         <td class="text-center">
                                             <el-button size="mini" icon="el-icon-edit" @click="editBanner({{ $val->id }})"></el-button>
                                             <el-button type="danger" size="mini" icon="el-icon-delete" @click="deleteBanner({{ $val->id }})"></el-button>
                                         </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $data->links() }}
                    </div>
                </div>
            </div>
        </div>

      <!--添加/修改-->
      <el-drawer title="添加/修改轮播图" size="71%" :visible.sync="bannerFormFlag"  id="school-campus-intro-app">
        <el-form ref="currentFlowForm" label-width="120px" style="padding: 10px;">
          <el-col>
            <el-form-item label="图片">
              <el-upload
                ref="upload"
                class="upload-demo"
                action="{{ route('uploadFiles') }}"
                :on-remove="handleRemove"
                :on-success="saveFile"
                :before-upload="handlePreview"
                :file-list="fileList"
                :limit="1"
                list-type="picture"
              >
              <div
                style="height: 40px;
                      background-color: #fff;
                      background-image: none;
                      border-radius: 4px;
                      border: 1px solid #dcdfe6;
                      box-sizing: border-box;
                      color: #606266;
                      display: inline-block;
                      font-size: inherit;
                      line-height: 40px;
                      outline: 0;
                      padding: 0 15px;
                      transition: border-color .2s cubic-bezier(.645,.045,.355,1);
                      width: 220px;"
              >点击上传封面图片</div>
                <div slot="tip" class="el-upload__tip">图片格式为jpg/png，图片宽高比2:1</div>
              </el-upload>
            </el-form-item>
            <el-form-item label="标题">
              <el-input style="width: 90%;" v-model="bannerFormInfo.title" placeholder="请填写标题"></el-input>
            </el-form-item>
            <el-form-item label="类型">
              <el-cascader style="width: 90%;" v-model="bannerFormInfo.type" :options="typeList" :props="{ checkStrictly: true }" @change="handlechange" clearable></el-cascader>
            </el-form-item>
            <el-form-item :label="isshow1Lable" v-if="isshow1">
              <el-input style="width: 90%;" v-model="bannerFormInfo.external" :placeholder="isshow1Placeholder"></el-input>
            </el-form-item>
            <el-form-item label="内容" v-if="isshow2">
              <textarea id="content" height="400" v-model="bannerFormInfo.content">adfasdfa</textarea>
            </el-form-item>

            <el-form-item label="浏览权限">
              <el-switch v-model="bannerFormInfo.public" active-text="只有登录可见" inactive-text="不需要登录">
              </el-switch>
            </el-form-item>
            <el-form-item label="发布状态">
              <el-switch v-model="bannerFormInfo.status" active-text="立即发布" inactive-text="暂不发布">
              </el-switch>
            </el-form-item>
            <el-form-item>
              <el-button type="primary" @click="saveBannerInfo">保存</el-button>
              <el-button @click="bannerFormFlag = false">取消</el-button>
            </el-form-item>
          </el-col>
        </el-form>
      </el-drawer>
    </div>
    <style>
      #school-campus-intro-app {height: 100%;}
    </style>
    <div id="app-init-data-holder" data-school="{{ session('school.id') }}" data-newflow="1"></div>
    <script>
      function sort(id,sort) {
        $.get("{{ route('school_manager.banner.top_banner_sort') }}", { id: id,sort:sort }, function(jsondata) {},'json');
        window.location.reload();
      }
    </script>
@endsection

