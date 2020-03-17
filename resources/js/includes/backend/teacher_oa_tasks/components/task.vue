<template>
  <div>
    <div
      class="list-item"
      v-for="(task, index) in list"
      :key="index"
      @click="goDetail(task)"
    >
      <div class="left">
        <div class="item">
          <span class="title">任务名称</span>
          <span class="content">{{ task.name }}</span>
        </div>
        <div class="item">
          <span class="title">截至时间</span>
          <span class="content">{{ task.endTime }}</span>
        </div>
        <div class="item">
          <span class="title">负责人</span>
          <span class="content">{{ task.endTime }}</span>
        </div>
        <div class="item">
          <span class="title">发起人</span>
          <span class="content">{{ task.endTime }}</span>
        </div>
      </div>
      <div class="right">
        <div class="status">{{ task.status }}</div>
        <div class="time">{{ task.createTime }}</div>
      </div>
    </div>
    <el-pagination background layout="prev, pager, next" :total="1000">
    </el-pagination>
  </div>
</template>
<script>
import { getTaskList } from "../common/api";
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
      list: []
    };
  },
  methods: {
    goDetail(task) {
      window.location.href = "/teacher/ly/oa/task/detail?taskId=" + task.id;
    },
    getTaskList() {
      getTaskList().then(data => {
        debugger;
      });
      console.log("get list for " + this.mode);
      this.list = [
        {
          name: "123",
          endTime: "超凡大师",
          createTime: "20193333",
          status: "wanjie",
          id: "123"
        },
        {},
        {},
        {},
        {},
        {},
        {},
        {},
        {}
      ];
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
    flex: 0;
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
</style>
