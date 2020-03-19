<template>
  <div class="form">
    <el-form ref="form" :model="form" label-width="80px">
      <el-form-item label="任务名称">
        <el-input v-model="form.task_title" placeholder="请输入（必填）"></el-input>
      </el-form-item>
      <el-form-item label="截止时间">
        <el-date-picker v-model="form.end_time" type="datetime" placeholder="请输入（必填）"></el-date-picker>
      </el-form-item>
      <el-form-item label="负责人">
        <el-select
          v-model="form.leader_userid"
          remote
          filterable
          :remote-method="remoteLeader"
          placeholder="请输入（必填）"
        >
          <el-option
            v-for="item in ownerOptions"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          ></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="任务成员">
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
        <!-- <el-cascader

          style="width: 90%;"
          :props="memberOptions"
        ></el-cascader>-->
      </el-form-item>
      <el-form-item label="任务描述">
        <el-input type="textarea" v-model="form.task_content" placeholder="请输入"></el-input>
      </el-form-item>
      <el-form-item label="关联项目">
        <el-select v-model="form.projectid" placeholder="请输入（选填）">
          <el-option
            v-for="item in projectOptions"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          ></el-option>
        </el-select>
      </el-form-item>
    </el-form>
    <div class="btn-box">
      <el-button type="primary" @click="onSubmit">立即创建</el-button>
    </div>
  </div>
</template>
<script>
import { TaskApi } from "../common/api";
import { Util } from "../../../../common/utils";
import MemberSelect from "./member-chose";
import moment from "moment";

export default {
  name: "task-form",
  components: {
    MemberSelect
  },
  methods: {
    onSubmit() {
      if (!this.form.task_title) {
        this.$message.error("请输入任务标题");
        return;
      }
      if (!this.form.end_time) {
        this.$message.error("请选择任务时间");
        return;
      }
      if (!this.form.leader_userid) {
        this.$message.error("请选择负责人");
        return;
      }
      if (!this.form.member_userids || this.form.member_userids.length < 1) {
        this.$message.error("请选择任务成员");
        return;
      }
      let formdata = JSON.parse(JSON.stringify(this.form));
      formdata.end_time = moment(formdata.end_time).format(
        "YYYY-MM-DD hh:mm:ss"
      );
      formdata.member_userids = formdata.member_userids.toString();
      TaskApi.excute("addOaTaskInfo", formdata).then(res => {
        this.$emit("done");
      });
    },
    remoteLeader(v) {
      TaskApi.excute("getOaProjectUserListInfo", {
        keyword: v
      }).then(res => {
        this.ownerOptions = res.data.data.map(per => {
          return {
            label: per.username,
            value: per.userid
          };
        });
      });
    }
  },
  data() {
    let that = this;
    return {
      form: {
        member_userids: []
      },
      ownerOptions: [],
      projectOptions: []
    };
  },
  created() {
    TaskApi.excute("getOaProjectListInfo").then(res => {
      this.projectOptions = res.data.data.map(pro => {
        return {
          label: pro.project_title,
          value: pro.projectid
        };
      });
    });
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

    .el-dropdown {
      width: 100%;
    }

    .el-date-editor {
      width: 100%;
    }
  }

  .btn-box {
    flex: none;
    padding: 12px;
    text-align: right;
  }
}
</style>
