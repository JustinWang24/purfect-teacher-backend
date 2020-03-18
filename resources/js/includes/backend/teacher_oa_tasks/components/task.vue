<template>
  <div>
    <div class="list-item" v-for="(task, index) in list" :key="index" @click="goDetail(task)">
      <div class="left">
        <div class="item">
          <span class="title">任务名称</span>
          <span class="content">{{ task.task_title }}</span>
        </div>
        <div class="item">
          <span class="title">截至时间</span>
          <span class="content">{{ task.end_time }}</span>
        </div>
        <div class="item">
          <span class="title">负责人</span>
          <span class="content">{{ task.leader_name }}</span>
        </div>
        <div class="item">
          <span class="title">发起人</span>
          <span class="content">{{ task.create_name }}</span>
        </div>
      </div>
      <div class="right">
        <div :class="'status ' + getStatusClass(task.status)">{{ getStatusText(task.status) }}</div>
        <div class="time">{{ task.create_time }}</div>
      </div>
    </div>
    <el-pagination
      background
      layout="prev, pager, next"
      :page-count="pagination.pageCount"
      :current-page="pagination.page"
      @current-change="onPageChange"
    ></el-pagination>
  </div>
</template>
<script>
import { TaskApi } from "../common/api";
import { TaskMode, TaskStatus } from "../common/enum";
import { Util } from "../../../../common/utils";
export default {
  name: "task-list",
  props: {
    mode: {
      type: String,
      required: true,
      default: ""
    }
  },
  data() {
    return {
      list: [],
      pagination: {
        page: 1,
        pageCount: 0
      }
    };
  },
  computed: {
    getStatusText() {
      return function(status) {
        return TaskStatus[status].text;
      };
    },
    getStatusClass() {
      return function(status) {
        return TaskStatus[status].classes;
      };
    }
  },
  watch: {
    "pagination.page": page => {
      this.getTaskList();
    }
  },
  methods: {
    goDetail(task) {
      window.location.href = "/teacher/ly/oa/task/detail?taskid=" + task.taskid;
    },
    getTaskList() {
      TaskApi.excute("getOaTaskListInfo", {
        page: this.pagination.page,
        type: TaskMode[this.mode].value
      }).then(res => {
        if (Util.isAjaxResOk(res)) {
          this.list = res.data.data;
          this.pagination.pageCount = res.data.lastPage;
        }
      });
    },
    onPageChange(page) {
      this.pagination.page = page;
    }
  }
};
</script>
<style lang="scss" scoped>
.list-item {
  font-size: 14px;
  border-bottom: 1px solid #eaedf2;
  padding: 12px;
  transition: all 0.5s;
  cursor: pointer;
  display: flex;
  flex-direction: row;
  .left {
    flex: 1;
    .item {
      display: flex;
      flex-direction: row;
      padding-bottom: 8px;
      .title {
        width: 90px;
        flex: none;
        color: #aaaaaa;
        font-weight: bold;
      }
      .content {
        flex: 1;
        color: #333333;
      }
    }
  }
  .right {
    display: flex;
    flex-direction: column;
    .status {
      flex: 1;
      padding: 12px 0;
    }
    .status.done {
      color: #6dcc58;
    }
    .status.timeout {
      color: #fd3434;
    }
    .status.pending {
      color: #fe7b1c;
    }
    .status.waiting {
      color: #fe7b1c;
    }
    .time {
      flex: 0;
      color: #eaedf2;
      font-size: 12px;
    }
  }
}
.list-item:last-child {
  border-bottom: none;
}
.list-item:hover {
  box-shadow: 0 0 6px #ccc;
}
.el-pagination {
  float: right;
  padding-top: 16px;
}
</style>
