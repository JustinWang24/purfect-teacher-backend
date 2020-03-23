<template>
  <div class="task-form">
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
              :disabled="item.disabled"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            ></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="任务成员">
          <el-input
            placeholder="请输入"
            :value="
              form.member_userids.length > 0
                ? form.member_userids.length + '人'
                : ''
            "
            @click.native="goSetMember"
            readonly
          ></el-input>
          <!-- <el-dropdown ref="dropdown" trigger="click" placement="bottom-start">
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
              <member-select
                parentId="task-dropdownSlot"
                :disabledList="disabledList"
                v-model="form.member_userids"
              ></member-select>
            </el-dropdown-menu>
          </el-dropdown>-->
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
    <member-select
      @onSelect="setMemberList"
      :disabledList="disabledList"
      class="member-select"
      :class="{'vie':selectMb}"
    ></member-select>
  </div>
</template>
<script>
import { TaskApi } from "../common/api";
import { Util } from "../../../../common/utils";
// import MemberSelect from "./member-chose";
import MemberSelect from "./setmember";
import moment from "moment";

export default {
  name: "task-form",
  components: {
    MemberSelect
  },
  computed: {
    disabledList() {
      return [this.currentUserId, this.form.leader_userid];
    }
  },
  watch: {
    "form.member_userids": {
      deep: true,
      immediate: true,
      handler(val, oldval) {
        this.ownerOptions.map(member => {
          if (val.includes(member.value.toString())) {
            member.disabled = true;
          } else {
            if (this.currentUserId === member.value) {
              member.disabled = true;
            } else {
              member.disabled = false;
            }
          }
        });
      }
    }
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
    goSetMember() {
      this.selectMb = true;
    },
    setMemberList(list) {
      this.form.member_userids = list;
      this.selectMb = false;
    },
    remoteLeader(v) {
      TaskApi.excute("getOaProjectUserListInfo", {
        keyword: v
      }).then(res => {
        this.ownerOptions = res.data.data.map(per => {
          return {
            label: per.username,
            value: per.userid,
            disabled:
              per.userid === this.currentUserId ||
              this.form.member_userids.includes(per.userid.toString())
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
      selectMb: false,
      ownerOptions: [],
      projectOptions: [],
      currentUserId: 0
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
    this.currentUserId = this.$attrs.currentuserid;
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
    text-align: center;
  }
}
.task-form {
  position: relative;
  height: 100%;
  .member-select {
    position: absolute;
    top: 0;
    right: -100%;
    width: 100%;
    transition: right 0.4s;
    background-color: #ffffff;
  }
  .member-select.vie {
    right: 0;
  }
}
</style>
