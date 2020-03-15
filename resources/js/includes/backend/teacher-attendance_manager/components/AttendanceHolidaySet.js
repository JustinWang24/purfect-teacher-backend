import { Mixins } from "../Mixins";
import { _save_attendance, catchErr ,_delete_exceptionday,_save_exceptionday} from "../api/index";
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
<el-calendar
    v-on:input="dateClicked"
    :first-day-of-week="7"
    v-loading="isLoading"
  >
    <!-- 这里使用的是 2.5 slot 语法，对于新项目请使用 2.6 slot 语法-->
    <template
      slot="dateCell"
      slot-scope="{date, data}"
      :class= "getDate(date) ? 'is-date-selected' : 'no-selected'"
    >
      <div :class= "getDate(date) ? 'is-date-selected' : 'no-selected'">
        {{ data.day.split('-').slice(1).join('月') }}日 
        <el-tag size="mini" v-if="getDate(date)">休息</el-tag>
      </div>
    </template>
  </el-calendar>
</el-drawer>
    `,
  mixins: [Mixins],
  data() {
    return {
      calendarDefaultDate: new Date(),
      isLoading:false,
      currentDate: moment(new Date()).format('YYYY-MM-DD')
    };
  },
  methods: {
    dateClicked(date) {
      if(this.isLoading) return false
      this.isLoading = true
      const chooseDate = moment(date).format('YYYY-MM-DD')
      if(moment(chooseDate).isBefore(this.currentDate)) {
        this.$message.error('小于当前日期则不可以修改')
        return false
      }
      const ofIndex = this.exceptiondays.indexOf(moment(date).format('YYYY-MM-DD'))
      if( ofIndex != -1) {
        const {exceptiondays} = this.formData
        // 查询当前的id
        _delete_exceptionday({id:exceptiondays[ofIndex].id}).then(res=> {
          this.exceptiondays.splice(ofIndex,1)
          this.isLoading = false
        })
      } else {
        _save_exceptionday({attendance_id:this.attendance_id,day:chooseDate}).then(res => {
          this.exceptiondays.push(moment(date).format('YYYY-MM-DD'))
          const {exceptiondays} = this.formData
          this.SETOPTIONOBJ({
            key: "formData",
            value: {
              exceptiondays:exceptiondays.concat(res)
            }
          });
          this.isLoading = false
        })
      }
    },
    getDate(date) {
      return this.exceptiondays.indexOf(moment(date).format('YYYY-MM-DD')) != -1
    },
    _handleClose() {
      this.isLoading = false
      this.SETOPTIONS({ visibleHolidaySet: false });
    }
  }
});
