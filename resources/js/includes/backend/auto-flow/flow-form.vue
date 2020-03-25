<template>
  <div class="auto-flow-form">
    <el-drawer
      :title="flow.name"
      ref="formDrawer"
      :destroy-on-close="true"
      :visible.sync="formDrawer"
      direction="rtl"
    >
      <auto-form :id="flow.id" />
    </el-drawer>
  </div>
</template>
<script>
import AutoForm from "./components/auto-form";

export default {
  name: "FlowForm",
  components: {
    AutoForm
  },
  data() {
    return {
      formDrawer: false,
      flow: {}
    };
  },
  methods: {
    init(flow) {
      this.flow = flow;
      this.formDrawer = true;
    }
  }
};
</script>

<style lang="scss">
.auto-flow-form {
  *:focus {
    outline: none !important;
  }
  .el-drawer {
    min-width: 400px;
    max-width: 600px;
    width: 50% !important;

    .el-drawer__header {
      color: #333333;
      font-weight: bold;
      padding: 12px !important;
      font-size: 18px;
      border-bottom: 1px solid #eaedf2;
      margin-bottom: 12px !important;
    }

    .el-drawer__body {
      height: 100%;
      overflow: auto;
      overflow-x: hidden;
    }
  }
}
</style>