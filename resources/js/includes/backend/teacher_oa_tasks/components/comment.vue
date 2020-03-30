<template>
  <div class="form">
    <el-form ref="form" :model="form" label-width="80px">
      <el-input type="textarea" v-model="form.content" rows="5" placeholder="请输入讨论内容"></el-input>
    </el-form>
    <div class="btn-box">
      <el-button type="primary" @click="onSubmit">立即创建</el-button>
    </div>
  </div>
</template>
<script>
import { TaskApi } from "../common/api";
export default {
  name: "comment-form",
  props: {
    taskid: {
      type: String,
      required: true
    },
    userid: {
      default: null
    }
  },
  methods: {
    onSubmit() {
      if (!this.form.content) {
        this.$message.error("请输入内容");
        return;
      }
      TaskApi.excute("addOaTaskForum", {
        taskid: this.taskid,
        forum_content: this.form.content,
        userid: this.userid || 0
      }).then(res => {
        this.$emit("reply");
      });
    }
  },
  data() {
    return {
      form: {}
    };
  }
};
</script>
<style lang="scss" scoped>
.form {
  display: flex;
  height: 100%;
  flex-direction: column;
  .el-form {
    flex: 1;
    padding: 12px;
  }
  .btn-box {
    flex: none;
    padding: 12px;
    text-align: center;
  }
}
</style>
