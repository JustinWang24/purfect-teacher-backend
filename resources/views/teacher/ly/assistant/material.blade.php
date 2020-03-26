@extends('layouts.app')
@section('content')
<div id="teacher-assistant-check-in-app" class="material-box">
  <div class="teacher-container">
    <div class="blade_title">教学资料</div>
    <div class="blade_container">
      <el-row  class="blade_container_tit">
        <el-col :span="24">课程/资料</el-col>
      </el-row>
      <el-row class="blade_container_tab">
        <el-col :span="12" class="table-left"><div>我的课程</div></el-col>
        <el-col :span="12" class="table-right"><div>教学资料</div></el-col>
      </el-row>

      <!--教学资料-->
      <el-tabs v-model="activeName" @tab-click="handleClick" stretch>
        <el-tab-pane label="课前预习（50）" name="first">课前预习（50）</el-tab-pane>
        <el-tab-pane label="课中课件（50）" name="second">课中课件（50）</el-tab-pane>
        <el-tab-pane label="课后复习（50）" name="third">课后复习（50）</el-tab-pane>
        <el-tab-pane label="复习汇总（50）" name="fourth">复习汇总（50）</el-tab-pane>
      </el-tabs>

      <el-row class="blade_container_box">

        <!--我的课程-->
        <div class="blade_container_cont">
          <h4 class="title">高等数学（40课时）</h4>
          <p class="content">生产运作管理是对生产运作系统的设计，运行与维护过程的管理，它包括对生产运作活动进行计划，组织和控制。传统生产管理主要是以工业企业，特别是制造为研究队形，其瞩目点主要是对生产运作活动进行计划，组织和控制。传统生产管理主要是以公约企业，特别是制造也为谭家对和控制。传统生产管理主要是以工业为假酒对象，特别是制造。</p>
          <div class="tags">
            <el-tag>课前预习10</el-tag>
            <el-tag>课后作业0</el-tag>
            <el-tag>课中课件3</el-tag>
            <el-tag>复习汇总5</el-tag>
          </div>
          <el-row class="button">
            <el-button type="primary" icon="el-icon-edit" size="mini" class="button-edit"></el-button>
            <el-button type="primary" size="mini">添加资料</el-button>
          </el-row>
        </div>

        <!--我的课程-->
        <div class="blade_container_cont">
          <el-table
            :data="tableData"
            stripe
            style="width: 100%">
            <el-table-column
              prop="date"
              label="日期"
              width="180">
            </el-table-column>
            <el-table-column
              prop="name"
              label="姓名"
              width="180">
            </el-table-column>
            <el-table-column
              prop="address"
              label="地址">
            </el-table-column>
          </el-table>
        </div>

      </el-row>
    </div>
  </div>
</div>

<div id="app-init-data-holder"
     data-school="{{ session('school.id') }}"
></div>
<style>
  .material-box{
    display: flex;
    padding: 0 10px;
    background: #fff;
    align-items: center;
    justify-content: space-around;
  }
  .teacher-container{
    flex: 1;
    padding: 21px;
    border-radius: 10px;
    margin: 40px 10px 20px;
    background-color: #eaedf2;
  }
  .blade_container{
    background: #fff;
    border-radius: 10px;
    min-height: 700px;
  }
  .blade_container_tit{
    color: #414A5A;
    font-size: 24px;
    font-weight: bold;
    padding: 15px 30px;
    border-bottom: #EAEDF2 1px solid;
  }
  .blade_container_tab{
    height: 60px;
    font-size: 24px;
    font-weight: bold;
    text-align: center;
    line-height: 60px;
    color: rgba(78,165,254,1);
    border-bottom: #EAEDF2 1px solid;
  }
  .blade_container_cont{
    padding: 20px 30px;
    border-bottom: #EAEDF2 1px solid;

  }
  .blade_container_box .title{
    font-size: 14px;
    font-weight: bold;
    letter-spacing:1px;
    color:rgba(49,59,76,1);
  }
  .blade_container_box .content{
    line-height:30px;
    letter-spacing:1px;
    color:rgba(49,59,76,1);
  }
  .blade_container_cont .tags > span{
    margin: 10px 20px 10px 0px;
  }
  .blade_container_cont .button{
    margin: 10px 0px;
  }
  .blade_container_cont .button-edit{
    border: 0px;
    background-color:#FE7B1C;
  }
</style>

@endsection
