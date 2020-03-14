import { Mixins } from "../Mixins";
import Organization from "./Organization";
import ManagerMent from "./ManagerMent";

import { _save_attendance, catchErr } from "../api/index";
Vue.component("AttendanceForm", {
  template: `
    <el-drawer
    title="添加/编辑"
    :visible.sync="visibleFormDrawer"
    direction="rtl"
    modal-append-to-body
    :wrapperClosable="false"
    custom-class="attendance-form-drawer"
    :before-close="_handleClose">
    <el-form v-loading="isEditFormLoading" :model="formData" :rules="rulesForm" ref="ruleForm" class="attendance-form"  label-width="100px">
    <el-form-item label="考勤组名称" prop="attendance.title">
      <el-input v-model="formData.attendance.title"></el-input>
    </el-form-item>
    <el-form-item label="指定WIFI" prop="attendance.wifi_name">
      <el-input v-model="formData.attendance.wifi_name"></el-input>
    </el-form-item>
    <el-form-item label="打卡班次" >
    <div class="using-switch">
    <el-switch
        size="mini"
        v-model="using_default"
        active-color="#4ca3ff"
        inactive-color="#ccc9cd"
        disabled
        inactive-text="上午" />
        <el-switch
        size="mini"
        v-model="formData.attendance.using_afternoon"
        active-color="#4ca3ff"
        inactive-color="#ccc9cd"
        inactive-text="下午" />
        <el-switch
        size="mini"
        v-model="using_default"
        active-color="#4ca3ff"
        inactive-color="#ccc9cd"
        disabled
        inactive-text="下班" />
    </div>
    </el-form-item>
     <Organization/>
     <ManagerMent />
    </el-form>
    <div class="btn-create">
        <el-button  @click="_add()" :loading="isLoading" type="primary" size="mini">保存</el-button>
     </div>
</el-drawer>
    `,
  mixins: [Mixins],
  methods: {
    _add() {
      if (this.isLoading || this.isEditFormLoading) return false;
      this.isLoading = true;
      this.$refs["ruleForm"].validate(async valid => {
        !valid && (this.isLoading = false);
        if (!valid) return false;
        const [err, data] = await catchErr(_save_attendance(this.formData));
        data &&
          (this.$message.success("保存成功"),
          this._handleClose(),
          this._initData());
        this.isLoading = false;
      });
    },
    _handleClose() {
      this.$refs["ruleForm"].resetFields();
      this.SETOPTIONS({ visibleFormDrawer: false });
    }
  },
  data() {
    return {
      drawer: false,
      using_default: true,
      isLoading: false,
      rulesForm: {
        "attendance.title": [
          { required: true, message: "请填写考勤组名称", trigger: "blur" }
        ],
        "attendance.wifi_name": [
          { required: true, message: "请填写指定WIFI", trigger: "blur" }
        ],
        organizations: [{ required: true, message: "请选择考勤部门" }],
        managers:[
          { required: true, message: "请选择管理员", trigger: "blur" }
        ],
      }
    };
  }
});
