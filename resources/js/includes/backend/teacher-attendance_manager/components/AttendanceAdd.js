import { Mixins } from "../Mixins";
Vue.component("AttendanceAdd", {
  template: `
    <div class="attendance-add">
        <div class="title">考勤组</div>
        <div class="btn-create">
        <el-button @click="_add()" icon="el-icon-plus" type="primary" size="mini">添加</el-button>
        </div>
    </div>`,
  mixins: [Mixins],
  methods: {
    _add() {
      // let school_id = 1 //接口
      const formData = {
        attendance: {
          id: "", //编辑时传递
          school_id,
          title: "",
          wifi_name: "",
          using_afternoon: true //是否启用中午打卡
        },
        organizations: []
      };
      this.SETOPTIONS({ formData, organizations: [] });
      this.SETOPTIONS({ visibleFormDrawer: true, isEditFormLoading: false });
    }
  }
});
