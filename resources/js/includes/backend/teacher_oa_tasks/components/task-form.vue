<template>
  <div class="form">
    <el-form ref="form" :model="form" label-width="80px">
      <el-form-item label="任务名称">
        <el-input v-model="form.name"></el-input>
      </el-form-item>
      <el-form-item label="截止时间">
        <el-select v-model="form.region" placeholder="请选择活动区域">
          <el-option label="区域一" value="shanghai"></el-option>
          <el-option label="区域二" value="beijing"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="负责人">
        <el-select v-model="form.region" placeholder="请选择活动区域">
          <el-option label="区域一" value="shanghai"></el-option>
          <el-option label="区域二" value="beijing"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="任务成员">
        <el-select v-model="form.region" placeholder="请选择活动区域">
          <el-option label="区域一" value="shanghai"></el-option>
          <el-option label="区域二" value="beijing"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="任务描述">
        <el-input type="textarea" v-model="form.desc"></el-input>
      </el-form-item>
      <el-form-item label="关联项目">
        <el-select v-model="form.region" placeholder="请选择活动区域">
          <el-option label="区域一" value="shanghai"></el-option>
          <el-option label="区域二" value="beijing"></el-option>
        </el-select>
      </el-form-item>
    </el-form>
    <div class="btn-box">
      <el-button type="primary" @click="onSubmit">立即创建</el-button>
    </div>
  </div>
</template>
<script>
export default {
  name: "task-form",
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
  }
  .btn-box {
    flex: none;
    padding: 12px;
    text-align: right;
  }
}
</style>
