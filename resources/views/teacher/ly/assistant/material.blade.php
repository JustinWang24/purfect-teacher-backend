@extends('layouts.app')
@section('content')
<div id="teacher-assistant-material-app" class="material-box">
  <div class="teacher-container">
    <div class="blade_title">教学资料</div>
    <div class="blade_container">

      <!--我的课程/教学资料--->
      <div v-show="activeIndex === 1 || activeIndex == 2 " >
        <el-row  class="blade_container_tit">
          <el-col :span="24">课程/资料</el-col>
        </el-row>
        <el-row class="blade_container_tab">
          <el-col :span="12" class="table-left"><div @click="changeMeans(1)">我的课程</div></el-col>
          <el-col :span="12" class="table-right"><div @click="changeMeans(2)">教学资料</div></el-col>
        </el-row>
        <!--教学资料-->
        <el-tabs v-model="activeName" @tab-click="activeTable" v-show="activeIndex === 2" stretch>
          <el-tab-pane v-for="(item,key) in myMaterialsList" :label="item.name"></el-tab-pane>
        </el-tabs>
        <!--我的课程-->
        <el-row class="blade_container_box" v-show="activeIndex === 1">
          <div class="blade_container_cont" v-for="(item,key) in myCourseList">
            <h4 class="title">@{{ item.course_name }}（@{{ item.duration }}课时）</h4>
            <p class="content">@{{ item.desc }}</p>
            <div class="tags">
              <el-tag v-for="(item1,key1) in item.types">@{{ item1.name }}@{{ item1.num }}</el-tag>
            </div>
            <el-row class="button">
              <el-button type="primary" icon="el-icon-edit" size="mini" class="button-edit" @click="changeMeans(3,item)"></el-button>
              <el-button type="primary" size="mini" @click="changeMeans(4,item)">添加资料</el-button>
            </el-row>
          </div>
        </el-row>
        <!--我的课程-->
        <el-row class="blade_container_box" vv-show="activeIndex === 2">
         <el-table :data="myMaterialsListData" style="width: 100%">
           <el-table-column>
             <template slot-scope="scope">
               <span class="cloumn-a">@{{ scope.row.desc }}</span>
             </template>
           </el-table-column>
          <el-table-column>
            <span class="cloumn-b">第25节课课前预习需要</span>
          </el-table-column>
           <el-table-column>
             <template slot-scope="scope">
                 <div slot="reference" class="name-wrapper">
                   <el-tag v-for="(item,key) in scope.row.grades">@{{ item.grade_name }}</el-tag>
                 </div>
             </template>
           </el-table-column>
          <el-table-column>
            <template slot-scope="scope">
            <el-button type="primary" icon="el-icon-edit" size="mini" class="button-edit"></el-button>
            <el-button type="primary" size="mini">下载</el-button>
            <el-button size="mini" type="danger">删除</el-button>
            </template>
          </el-table-column>
          </el-table>
        </el-row>
      </div>

      <!--添加计划日志--->
      <div class="row" v-show="activeIndex === 3 ">
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
          <div class="card">
            <div class="card-body">
              <h2>教学计划</h2>
              <hr>
              <Redactor v-model="notes.teacher_notes" placeholder="请输入内容" :config="configOptions" />
              @{{ notes.teacher_notes }}
              <div class="mt-3" v-show="showEditor">
                <el-button type="primary" @click="saveNotes">保存/关闭</el-button>
              </div>
              <hr>
            </div>
          </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
          <div class="card">
            <div class="card-body">
              <h2>教学日志 <el-button class="pull-right" type="primary" size="small" icon="el-icon-edit" @click="showLogEditorHandler()">写日志</el-button></h2>
              <hr>
              <div v-show="showLogEditor">
                <el-form :model="logModel" label-width="80px" class="course-form" style="margin-top: 20px;">
                  <el-form-item label="标题">
                    <el-input placeholder="必填: 标题" v-model="logModel.title"></el-input>
                  </el-form-item>
                  <el-form-item label="标题">
                    <el-input placeholder="必填: 日志内容" type="textarea" v-model="logModel.content"></el-input>
                  </el-form-item>
                  <el-button style="margin-left: 10px;" size="small" type="success" @click="saveLog">保存</el-button>
                  <el-button style="margin-left: 10px;" size="small" @click="showLogEditor=false">关闭</el-button>
                </el-form>
                <hr>
              </div>
              <el-card class="box-card mb-4" v-for="log in logs" :key="log.id" shadow="hover">
                <div class="text item pb-3">
                  <h4>标题: @{{ log.title }}</h4>
                  <p style="color: #ccc; font-size: 10px;">最后更新于: @{{ log.updated_at ? log.updated_at : '刚刚' }}</p>
                  <p>内容: @{{ log.content }}</p>
                  <el-button style="float: left;" size="mini" type="primary" @click="showLogEditorHandler(log)">编辑</el-button>
                  <el-button style="float: right;" size="mini" type="danger" @click="deleteLog(log)">删除</el-button>
                </div>
              </el-card>
            </div>
          </div>
        </div>
      </div>

    <div  v-show="activeIndex === 4 ">
        <material :course="course" :grades="grades" :lecture="lecture" v-if="lecture" :loading="loadingData" user-uuid="{{ $teacher->uuid }}"></material>
    </div>

    </div>
  </div>
</div>

<div id="app-init-data-holder"
     data-school="{{ session('school.id') }}"
     data-teacher='{!! $teacher !!}'
></div>
<style>
  .row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
  }
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
	cursor:pointer;
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
  .cloumn-a{
	cursor:pointer;
	font-size:14px;
	margin-left: 10px;
	font-family:MicrosoftYaHei;
	color:rgba(78,165,254,1);
	letter-spacing:1px;
	text-decoration:underline;
  }
  .cloumn-b{
	font-size:14px;
	font-family:MicrosoftYaHei-Bold,MicrosoftYaHei;
	font-weight:bold;
	color:rgba(49,59,76,1);
	letter-spacing:1px;
  }

</style>

@endsection
