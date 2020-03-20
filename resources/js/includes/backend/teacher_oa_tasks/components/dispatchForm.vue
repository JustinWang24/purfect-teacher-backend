<template>
  <div class="form">
    <el-form ref="form" :model="form" label-width="80px">
      <el-form-item label="搜索">
        <el-dropdown ref="dropdown" trigger="click" placement="bottom-start">
          <el-input
            id="task-dropdownSlot"
            placeholder="请输入"
            :value="
              form.member_userids.length > 0
                ? form.member_userids.length + '人'
                : ''
            "
            readonly
          ></el-input>
          <el-dropdown-menu slot="dropdown" ref="dropdownSlot" id="task-dropdown-menu">
            <member-select parentId="task-dropdownSlot" v-model="form.member_userids"></member-select>
          </el-dropdown-menu>
        </el-dropdown>
      </el-form-item>
    </el-form>
    <div class="btn-box">
      <el-button type="primary" @click="onSubmit">确定</el-button>
    </div>
  </div>
</template>
<script>
import MemberSelect from "./member-chose";
import { TaskApi } from "../common/api";
import { getFileURL } from "../common/utils";
import { Util } from "../../../../common/utils";
export default {
  name: "comment-form",
  props: {
    taskid: {
      type: String,
      required: true
    }
  },
  components: {
    MemberSelect
  },
  methods: {
    onSubmit() {
      if (!this.form.member_userids || this.form.member_userids.length < 1) {
        this.$message.error("请选择指派人");
        return;
      }
      TaskApi.excute("addOaTaskUser", {
        taskid: this.taskid,
        userid: this.form.member_userids.toString()
      }).then(res => {
        this.$emit("submit");
      });
    }
  },
  data() {
    return {
      form: {
        member_userids: []
      }
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
  .el-dropdown {
    width: 100%;
  }
  .btn-box {
    flex: none;
    padding: 12px;
    text-align: right;
  }
}
</style>
