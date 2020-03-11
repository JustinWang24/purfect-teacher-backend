import { Mixins } from "../Mixins";
import Organization from "./Organization";
import { _save_clocksets, catchErr } from "../api/index";
import moment from "moment";
Vue.component("AttendanceClockSet", {
  template: `
    <el-drawer
    title="时间"
    :visible.sync="visibleClockDrawer"
    direction="rtl"
    modal-append-to-body
    custom-class="attendance-form-drawer"
    :before-close= "_handleClose">
    <el-form v-loading="isEditFormLoading" ref="ruleForm" class="attendance-clockset-form"  label-width="100px">
    <div v-for="(item,index) in clockSetData" class="clock-set">
    <div class="set-top">
      <div class="week">{{weekDays[item.week]}}</div>
      <div class="week-copy" v-if="item.week!=='Monday'"><el-button type="text" @click="_copy(index)">复制前一天</el-button></div>
      <div class="is-weekday"><el-checkbox v-model="item.is_weekday">工作日</el-checkbox></div>
    </div>
    <div class="set-day">
      <div class="title">全天</div>
      <el-form-item label="开始时间">
      <el-input  size="mini" v-model="item.start" @change="_changeDate(item,'start')"></el-input>
    </el-form-item>
    <el-form-item label="结束时间">
      <el-input  size="mini" v-model="item.end" @change="_changeDate(item,'end')"></el-input>
    </el-form-item>
    </div>
    <div class="set-day">
      <div class="title">上午</div>
      <el-form-item label="上班时间">
      <el-input  size="mini" v-model="item.morning" @change="_changeDate(item,'morning')"></el-input>
    </el-form-item>
    <el-form-item label="迟到">
      <el-input  size="mini" v-model="item.morning_late"  @change="_changeDate(item,'morning_late')"></el-input>
    </el-form-item>
    </div>
    <div class="set-day" v-if="usingAfternoon">
      <div class="title">下午</div>
      <el-form-item label="开始时间">
      <el-input  size="mini" v-model="item.afternoon_start"  @change="_changeDate(item,'afternoon_start')"></el-input>
    </el-form-item>
    <el-form-item label="上班时间">
      <el-input  size="mini" v-model="item.afternoon" @change="_changeDate(item,'afternoon')"></el-input>
    </el-form-item>
    <el-form-item label="迟到">
      <el-input  size="mini" v-model="item.afternoon_late" @change="_changeDate(item,'afternoon_late')"></el-input>
    </el-form-item>
    </div>
    </div>
    </el-form>
    <div class="clock-btn">
        <el-button  @click="_saveClockSet()" :loading="isLoading" type="primary" size="mini">保存</el-button>
     </div>
</el-drawer>
    `,
  mixins: [Mixins],
  // ①结束时间＞迟到时间＞上班时间＞开始时间
  // ②下班时间＞中午时间＞上午时间
  methods: {
    _changeDate(item, key) {
      if (/^([0-1]?[0-9]|2[0-3]):([0-5][0-9])$/.test(item[key])) {
        if (item[key].split(":")[0] < 10) {
          item[key] = "0" + item[key];
        }
        item[key] += ':00'
      }
    },
    _copy(index) {
      const {
        start,
        morning,
        morning_late,
        afternoon_start,
        afternoon,
        afternoon_late,
        end
      } = this.clockSetData[index - 1];
      const checkNullData = {
        start,
        morning,
        morning_late,
        afternoon_start,
        afternoon,
        afternoon_late,
        end
      };
      if (!this.usingAfternoon) {
        delete checkNullData.afternoon_start;
        delete checkNullData.afternoon;
        delete checkNullData.afternoon_late;
      }
      if (!this._checkData([checkNullData])) return false;
      Object.keys(checkNullData).forEach(item => {
        this.clockSetData[index][item] = checkNullData[item];
      });
    },
    _checkData(data) {
      const checkData = JSON.parse(JSON.stringify(data))
      let nullFlag = false,
        regFlag = false,
        dateFlag = false;
      let regTest = /^(20|21|22|23|[0-1]\d):[0-5]\d:[0-5]\d$/;
      const currentDay = moment(new Date()).format("YYYY-MM-DD");
      // 时间检查
      const DateFullDay = {};
      checkData.forEach(item => {
        Object.keys(item).forEach(date => {
          item.nullFlag = item[date] == "";
          item.regFlag = !regTest.test(item[date]);
          DateFullDay[date] = moment(currentDay + " " + item[date]).valueOf();
        });
        const dateFlagReult = this.usingAfternoon
          ? DateFullDay.start <=
            DateFullDay.morning <
            DateFullDay.morning_late <
            DateFullDay.afternoon_start <
            DateFullDay.afternoon <
            DateFullDay.afternoon_late <
            DateFullDay.end
          : DateFullDay.start <=
            DateFullDay.morning <
            DateFullDay.morning_late <
            DateFullDay.end;
        item.dateFlagReult = !dateFlagReult;
      });
      console.log(checkData);
      checkData.forEach(item => {
        if (item.nullFlag) {
          nullFlag = true;
        }
        if (item.regFlag) {
          regFlag = true;
        }
        if (item.dateFlag) {
          dateFlag = true;
        }
      });
      if (nullFlag) {
        this.$message.error("请输入考勤时间");
        return false;
      }
      if (regFlag) {
        this.$message.error("请输入正确的考勤时间");
        return false;
      }
      if (dateFlag) {
        this.$message.error("输入的时间顺序不正确");
        return false;
      }
      return true;
    },
   async _saveClockSet() {
      if (this.isLoading || this.isEditFormLoading) return false;
      this.isLoading = true;
      const clockSetData = this.clockSetData.map(item => {
        const {
          start,
          morning,
          morning_late,
          afternoon_start,
          afternoon,
          afternoon_late,
          end
        } = item;
        if (this.usingAfternoon) {
          return {
            start,
            morning,
            morning_late,
            afternoon_start,
            afternoon,
            afternoon_late,
            end
          };
        } else {
          return {
            start,
            morning,
            morning_late,
            end
          };
        }
      });
      if (!this._checkData(clockSetData)) return false;
        const [err, data] = await catchErr(_save_clocksets({attendance_id:this.attendance_id,clocksets:this.clockSetData}));
        data &&
          (this.$message.success("保存成功"),
          this._handleClose(),
          this._initData());
        this.isLoading = false;
    },
    _handleClose() {
      this.SETOPTIONS({ visibleClockDrawer: false });
    }
  },
  data() {
    return {
      weekDays: {
        Monday: "星期一",
        Tuesday: "星期二",
        Wednesday: "星期三",
        Thursday: "星期四",
        Friday: "星期五",
        Saturday: "星期六",
        Sunday: "星期日"
      },
      isLoading: false
    };
  }
});
