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
  :before-close="_handleClose"
  :wrapperClosable="false"
>
  <el-form
    v-loading="isEditFormLoading"
    ref="ruleForm"
    class="attendance-clockset-form"
    label-width="100px"
  >
    <div
      v-for="(item,index) in clockSetData"
      :key="index"
      class="clock-set"
    >
      <div class="set-top">
        <div class="week">{{weekDays[item.week]}}</div>
        <div
          class="week-copy"
          v-if="item.week!=='Monday'"
        >
          <el-button
            type="text"
            @click="_copy(index)"
          >复制前一天</el-button>
        </div>
        <div class="is-weekday">
          <el-checkbox v-model="item.is_weekday" :true-label="1" :false-label= "0">工作日</el-checkbox>
        </div>
      </div>
      <div class="set-day">
        <div class="title">全天</div>
        <el-form-item label="开始时间">
          <el-input
            size="mini"
            v-model="item.start"
            @change="_changeDate(item,'start')"
          ></el-input>
        </el-form-item>
        <el-form-item label="结束时间">
          <el-input
            size="mini"
            v-model="item.end"
            @change="_changeDate(item,'end')"
          ></el-input>
        </el-form-item>
      </div>
      <div class="set-day">
        <div class="title">上午</div>
        <el-form-item label="上班时间">
          <el-input
            size="mini"
            v-model="item.morning"
            @change="_changeDate(item,'morning')"
          ></el-input>
        </el-form-item>
        <el-form-item label="迟到">
          <el-input
            size="mini"
            v-model="item.morning_late"
            @change="_changeDate(item,'morning_late')"
          ></el-input>
        </el-form-item>
      </div>
      <template
        v-if="usingAfternoon"
      >
        <div  class="set-day">
        <div class="title">下午</div>
        <el-form-item label="开始时间">
          <el-input
            size="mini"
            v-model="item.afternoon_start"
            @change="_changeDate(item,'afternoon_start')"
          ></el-input>
        </el-form-item>
        <el-form-item label="上班时间">
          <el-input
            size="mini"
            v-model="item.afternoon"
            @change="_changeDate(item,'afternoon')"
          ></el-input>
        </el-form-item>
        <el-form-item label="迟到">
          <el-input
            size="mini"
            v-model="item.afternoon_late"
            @change="_changeDate(item,'afternoon_late')"
          ></el-input>
        </el-form-item>
        </div>
        <div class="set-day">
          <div class="title" style="color:#fff;">下午</div>
          <el-form-item label="下班时间">
          <el-input
            size="mini"
            v-model="item.evening"
            @change="_changeDate(item,'evening')"
          ></el-input>
        </el-form-item>
        </div>
        
      </template>
      <div
        class="set-day"
        v-if="!usingAfternoon"
      >
        <div class="title">下午</div>
        <el-form-item label="下班时间">
          <el-input
            size="mini"
            v-model="item.evening"
            @change="_changeDate(item,'evening')"
          ></el-input>
        </el-form-item>
      </div>
    </div>
  </el-form>
  <div class="clock-btn">
    <el-button
      @click="_saveClockSet()"
      :loading="isLoading"
      type="primary"
      size="mini"
    >保存</el-button>
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
        item[key] += ":00";
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
        evening, //下班时间
        end, //结束时间
        is_weekday
      } = this.clockSetData[index - 1];
      const checkNullData = {
        start,
        morning,
        morning_late,
        afternoon_start,
        afternoon,
        afternoon_late,
        evening,
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
      // 工作日复制
      this.clockSetData[index]["is_weekday"] = is_weekday;
    },
    /**
     * 为空检查 格式检查 日期比较
     * @param {Aarray} data 
     */
    _checkData(data) {
      const checkData = JSON.parse(JSON.stringify(data));
      let nullFlag = false,
        regFlag = false,
        dateFlag = false,
        checkResult = [];
      let regTest = /^(20|21|22|23|[0-1]\d):[0-5]\d:[0-5]\d$/;
      const currentDay = moment(new Date()).format("YYYY/MM/DD");
      checkData.forEach((item, index) => {
        let DateFullDay = {},
          cuNullFlag = false,
          cuRegFlag = false,
          cuDateFlag = false;
        // 只要有一个不满足就返回
        cuNullFlag = Object.keys(item).some(key => item[key] == "");
        cuRegFlag = Object.keys(item).some(key => !regTest.test(item[key]));
        // 加当前日期 转为时间戳进行判断
        Object.keys(item).forEach(key => {
          DateFullDay[key] = moment(currentDay + " " + item[key]).valueOf();
        });
        if (this.usingAfternoon) {
          const {
            start,
            morning,
            morning_late,
            afternoon_start,
            afternoon,
            afternoon_late,
            evening,
            end
          } = DateFullDay;
          cuDateFlag = !(
            start <= morning &&
            morning < morning_late &&
            morning_late < afternoon_start &&
            afternoon_start < afternoon &&
            afternoon < afternoon_late &&
            afternoon_late < evening &&
            evening <= end
          );
        } else {
          const { start, morning, morning_late, evening,end } = DateFullDay;
          cuDateFlag = !(
            start <= morning &&
            morning < morning_late &&
            morning_late < evening &&
            evening <= end
          );
        }
        checkResult[index] = { cuNullFlag, cuRegFlag, cuDateFlag };
      });
      // 判断为true阻断
      for (let cIndex = 0; cIndex < checkResult.length; cIndex++) {
        const element = checkResult[cIndex];
        if(element.cuNullFlag) {
          nullFlag = true
          break
        }
        if(element.cuRegFlag) {
          regFlag = true
          break
        }
        if(element.cuDateFlag) {
          dateFlag = true
          break
        }
      }
      if (nullFlag || regFlag || dateFlag) {
        this.$message.error(nullFlag ? "请输入考勤时间" :regFlag ? '请输入正确的考勤时间' : '输入的时间顺序不正确' );
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
          evening,
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
            evening,
            end
          };
        } else {
          return {
            start,
            morning,
            morning_late,
            evening,
            end
          };
        }
      });
      if (!this._checkData(clockSetData)) {
        this.isLoading = false;
        return false;
      }
      const [err, data] = await catchErr(
        _save_clocksets({
          attendance_id: this.attendance_id,
          clocksets: this.clockSetData
        })
      );
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
