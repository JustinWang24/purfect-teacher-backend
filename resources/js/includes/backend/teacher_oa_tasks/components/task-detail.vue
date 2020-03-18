<template>
  <div class="task-detail-container">
    <div class="detail detail-panel">
      <div class="title">
        <span>已接收</span>
      </div>
      <div class="detail-item">
        <span class="title">任务名称</span>
        <span class="content">{{task.task_title}}</span>
      </div>
      <div class="detail-item">
        <span class="title">创建人</span>
        <span class="content">{{task.create_name}}</span>
      </div>
      <div class="detail-item">
        <span class="title">截止时间</span>
        <span class="content">{{task.end_time}}</span>
      </div>
      <div class="detail-item">
        <span class="title">负责人</span>
        <span class="content">{{task.leader_name}}</span>
      </div>
      <div class="detail-item">
        <span class="title">执行人</span>
        <span class="content">{{task.member_list.length}}人</span>
      </div>
      <div class="detail-item">
        <span class="title"></span>
        <span class="content executer">
          <span
            class="item"
            v-for="(member, index) in task.member_list"
            :key="index"
          >{{member.username}}</span>
        </span>
      </div>
      <div class="detail-item">
        <span class="title">任务描述</span>
        <span class="content">{{task.task_content}}</span>
      </div>
      <div class="detail-item">
        <span class="title">关联项目</span>
        <span class="content">{{task.project_title}}</span>
      </div>
      <div class="btn-box">
        <el-button class="dispatch" size="small" @click="()=>{dispathModal = true}">指派他人</el-button>
        <el-button type="primary" size="small" @click="()=>{finishModal = true}">确认完成</el-button>
      </div>
    </div>
    <div class="operate detail-panel">
      <div class="title">
        <span>操作日志</span>
      </div>
      <div class="detail-item" v-for="(log, index) in task.log_list" :key="index">
        <UserLink :name="log.username" />
        <span class="operation">{{log.log_content}}</span>
        <span class="operate-time">{{log.create_time}}</span>
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
              userid = 0;
            }
          "
        >我要讨论</el-button>
      </div>
      <div class="detail-item comment" v-for="(comment, index) in task.forum_list" :key="index">
        <div class="avatar" :style="{backgroundImage:comment.user_pics}"></div>
        <Avatar :src="comment.user_pics" />
        <div class="right">
          <div class="header">
            <span class="name">{{comment.username}}</span>
            <span class="time">{{comment.create_time}}</span>
          </div>
          <div class="msg">
            <div class="reply" v-if="comment.reply_username">
              回复
              <UserLink :name="comment.reply_username" />：
            </div>
            <span>{{comment.forum_content}}</span>
          </div>
          <div class="ctrl">
            <i
              class="el-icon-chat-dot-square"
              @click="
                () => {
                  discussModal = true;
                  chatUserId = comment.userid;
              }"
            ></i>
            <i
              v-if="isMyComment(comment.userid)"
              class="el-icon-delete"
              @click="deleteComment(comment.forumid)"
            ></i>
          </div>
        </div>
      </div>
    </div>
    <el-drawer
      title="编辑讨论的内容"
      ref="discussDrawer"
      :destroy-on-close="true"
      :visible.sync="discussModal"
      direction="rtl"
    >
      <comment :taskid="taskid" :userid="chatUserId" @reply="onReply" />
    </el-drawer>
    <el-drawer
      title="确认完成"
      ref="finishModal"
      :destroy-on-close="true"
      :visible.sync="finishModal"
      direction="rtl"
    >
      <FinishForm @submit="onFinish" :taskid="taskid" />
    </el-drawer>
  </div>
</template>
<script>
import comment from "./comment";
import UserLink from "./userLink";
import FinishForm from "./finishForm";
import Avatar from "./avatar";
import { Util } from "../../../../common/utils";
import { TaskApi } from "../common/api";
import { getQueryString } from "../common/utils";

export default {
  name: "TaskDetail",
  components: {
    comment,
    Avatar,
    UserLink,
    FinishForm
  },
  data() {
    return {
      discussModal: false,
      dispathModal: false,
      finishModal: false,
      task: {
        member_list: [],
        log_list: [],
        forum_list: []
      },
      taskid: "",
      chatUserId: ""
    };
  },
  computed: {
    isMyComment() {
      return function(userid) {
        debugger;
        return this.task.login_userid === userid;
      };
    }
  },
  methods: {
    onDispath() {},
    onFinish() {
      // TaskApi.excute('finishOaTaskInfo')
    },
    onReply() {
      this.$refs.discussDrawer.closeDrawer();
      this.discussModal = false;
      this.getOaTaskInfo();
    },
    deleteComment(forumid) {
      this.$confirm("确认删除该回复?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      }).then(() => {
        TaskApi.excute("delOaTaskForum", {
          forumid
        }).then(res => {
          this.getOaTaskInfo();
        });
      });
    },
    getOaTaskInfo() {
      TaskApi.excute("getOaTaskInfo", {
        taskid: this.taskid
      }).then(res => {
        if (Util.isAjaxResOk(res)) {
          this.task = res.data.data;
        }
      });
    }
  },
  created() {
    const taskid = getQueryString("taskid");
    this.taskid = taskid;
    this.getOaTaskInfo();
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
