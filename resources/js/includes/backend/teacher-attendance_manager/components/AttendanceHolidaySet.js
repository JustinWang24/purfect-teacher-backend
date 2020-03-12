import { Mixins } from "../Mixins";
import { _save_attendance, catchErr } from "../api/index";
Vue.component("AttendanceHolidaySet", {
  template: `
<el-drawer
title="节假日"
:visible.sync="visibleHolidaySet"
direction="rtl"
modal-append-to-body
custom-class="attendance-form-drawer"
:wrapperClosable="false"
:before-close= "_handleClose">

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
      this.SETOPTIONS({ visibleHolidaySet: false });
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
        organizations: [{ required: true, message: "请选择考勤部门" }]
      }
    };
  }
});
