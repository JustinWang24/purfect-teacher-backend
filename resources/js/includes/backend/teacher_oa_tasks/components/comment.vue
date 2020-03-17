<template>
  <div class="form">
    <el-form ref="form" :model="form" label-width="80px">
      <el-form-item>
        <el-input
          type="textarea"
          v-model="form.desc"
          rows="5"
          placeholder="请输入讨论内容"
        ></el-input>
      </el-form-item>
    </el-form>
    <div class="btn-box">
      <el-button type="primary" @click="onSubmit">立即创建</el-button>
    </div>
  </div>
</template>
<script>
export default {
  name: "comment-form",
  methods: {
    onSubmit() {}
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
    .el-select {
      width: 100%;
    }
    .el-form-item__label {
      font-weight: bold;
      color: #666666;
    }
    .el-form-item__content {
      margin-left: 0px;
    }
  }
  .btn-box {
    flex: none;
    padding: 12px;
    text-align: right;
  }
}
</style>
