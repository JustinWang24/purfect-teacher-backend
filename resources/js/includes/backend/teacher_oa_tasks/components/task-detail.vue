<template>
  <div class="task-detail-container">
    <div class="detail detail-panel">
      <div class="title">
        <span>已接收</span>
      </div>
      <div class="detail-item">
        <span class="title">任务名称</span>
        <span class="content">content</span>
      </div>
      <div class="detail-item">
        <span class="title">创建人</span>
        <span class="content">content</span>
      </div>
      <div class="detail-item">
        <span class="title">截止时间</span>
        <span class="content">content</span>
      </div>
      <div class="detail-item">
        <span class="title">负责人</span>
        <span class="content">content</span>
      </div>
      <div class="detail-item">
        <span class="title">执行人</span>
        <span class="content ">content</span>
      </div>
      <div class="detail-item">
        <span class="title"></span>
        <span class="content executer">
          <span class="item">张三</span>
          <span class="item">张三</span>
          <span class="item">王晓伟</span>
          <span class="item">张三</span>
          <span class="item">张三</span>
        </span>
      </div>
      <div class="detail-item">
        <span class="title">任务描述</span>
        <span class="content ">content</span>
      </div>
      <div class="detail-item">
        <span class="title">关联项目</span>
        <span class="content "
          >contentcontentcontentcontentcontentcontentcontentcontentcontentcontentcontent</span
        >
      </div>
      <div class="btn-box">
        <el-button class="dispatch" size="small" @click="dispath"
          >指派他人</el-button
        >
        <el-button type="primary" size="small" @click="finish"
          >确认完成</el-button
        >
      </div>
    </div>
    <div class="operate detail-panel">
      <div class="title">
        <span>操作日志</span>
      </div>
      <div class="detail-item">
        <span class="operater">小明</span>
        <span class="operation"
          >做了什么做了什么做了什么做了什么做了什么做了什么</span
        >
        <span class="operate-time">2019-01-01 02:30</span>
      </div>
      <div class="detail-item">
        <span class="operater">小明</span>
        <span class="operation"
          >做了什么做了什么做了什么做了什么做了什么做了什么</span
        >
        <span class="operate-time">2019-01-01 02:30</span>
      </div>
      <div class="detail-item">
        <span class="operater">小明</span>
        <span class="operation"
          >做了什么做了什么做了什么做了什么做了什么做了什么</span
        >
        <span class="operate-time">2019-01-01 02:30</span>
      </div>
      <div class="detail-item">
        <span class="operater">小明</span>
        <span class="operation"
          >做了什么做了什么做了什么做了什么做了什么做了什么</span
        >
        <span class="operate-time">2019-01-01 02:30</span>
      </div>
    </div>
    <div class="discuss detail-panel">
      <div class="title">
        <span>讨论信息</span>
        <el-button
          class="discuss"
          type="primary"
          size="small"
          @click="
            () => {
              discussModal = true;
            }
          "
          >我要讨论</el-button
        >
      </div>
      <div class="detail-item comment">
        <div class="avatar"></div>
        <div class="right">
          <div class="header">
            <span class="name">姓名</span>
            <span class="time">2019-03-03 19:39</span>
          </div>
          <div class="msg">这是消息，留言</div>
          <div class="ctrl">
            <i class="el-icon-chat-dot-square"></i>
            <i class="el-icon-delete"></i>
          </div>
        </div>
      </div>
      <div class="detail-item comment">
        <div class="avatar"></div>
        <div class="right">
          <div class="header">
            <span class="name">姓名</span>
            <span class="time">2019-03-03 19:39</span>
          </div>
          <div class="msg">这是消息，留言</div>
          <div class="ctrl">
            <i class="el-icon-chat-dot-square"></i>
            <i class="el-icon-delete"></i>
          </div>
        </div>
      </div>
    </div>
    <el-drawer
      title="编辑讨论的内容"
      :visible.sync="discussModal"
      direction="rtl"
    >
      <comment />
    </el-drawer>
  </div>
</template>
<script>
import comment from "./comment";

export default {
  name: "TaskDetail",
  components: {
    comment
  },
  data() {
    return {
      discussModal: false
    };
  },
  methods: {
    dispath() {},
    finish() {}
  }
};
</script>
<style lang="scss" scoped>
.task-detail-container {
  display: flex;
  min-width: 700px;
  .detail-panel {
    flex: 1;
    background-color: #ffffff;
    min-height: 50%;
    margin-right: 14px;
    border-radius: 4px;
    > .title {
      font-size: 18px;
      color: #313b4c;
      padding: 14px;
      .el-button {
        float: right;
      }
    }
    .detail-item {
      display: flex;
      border-top: 1px solid #eaedf2;
      padding-left: 22px;
      font-size: 14px;
      padding: 12px;
      .title {
        display: inline-block;
        width: 72px;
        color: #8a93a1;
      }
      .content {
        flex: 1;
        word-break: break-all;
        word-wrap: break-word;
        color: #313b4c;
      }
      .content.executer {
        .item {
          border-radius: 50%;
          display: flex;
          width: 50px;
          float: left;
          height: 50px;
          background-color: #72a5f8;
          color: #ffffff;
          font-size: 14px;
          align-items: center;
          justify-content: center;
          margin: 0 10px 10px 0;
          word-break: keep-all;
        }
      }
      .operater {
        color: #4ea5fe;
        cursor: pointer;
      }
      .operater:hover {
        text-decoration: underline;
      }
      .operation {
        color: #8a93a1;
        padding: 0 8px;
        flex: 1;
      }
      .operate-time {
        color: #d2d5de;
        font-size: 12px;
      }
    }
    .detail-item.comment {
      display: flex;
      .avatar {
        height: 50px;
        width: 50px;
        border-radius: 50%;
        background-color: red;
        margin-right: 8px;
      }
      .right {
        flex: 1;
        .header {
          margin-top: 3px;
          .name {
            color: #313b4c;
          }
          .time {
            float: right;
            color: #d2d5de;
            font-size: 12px;
          }
        }
        .msg {
          color: #8f97a5;
        }
        .ctrl {
          text-align: right;
          color: #9fa6b1;
          font-size: 20px;
          i {
            cursor: pointer;
          }
        }
      }
    }
    .btn-box {
      text-align: center;
      padding: 12px 0;
      .dispatch {
        background-color: #ef823a;
        border-color: #ef823a;
        color: #ffffff;
      }
    }
  }
}
</style>
