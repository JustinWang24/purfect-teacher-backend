// import { Util } from "../../../common/utils";
// import { Constants } from "../../../common/constants";

import store from "./store";
import { Mixins } from "./Mixins";
import WeekTimeTable from "./components/WeekTimeTable";
import WeekTimeHeader from "./components/WeekTimeHeader";
import WeekTitle from "./components/WeekTitle";
import WeekPrev from "./components/WeekPrev";
import WeekNext from "./components/WeekNext";

import "./index.css";
const teacherWeekTimetable = document.getElementById("teacherWeekTimetable");
if (teacherWeekTimetable) {
  new Vue({
    mixins: [Mixins],
    store,
    el: "#teacherWeekTimetable",
    template: `
        <div class="teacher-week-content">
        <WeekPrev />
        <div class="teacher-week-timetable-container">
            <div class="week-main-title">
                我的课表
            </div>
            <div class="teacher-week-timetable-main">
            <WeekTitle />
            <div class="teacher-week-timetable" v-loading="isTableLoading" :style="{'min-height':minHeight + 'px'}">
                <WeekTimeHeader />
                <WeekTimeTable />
            </div>
            </div>
        </div>
         <WeekNext />
        </div>
        `,
    created() {
      const currentDate = moment(new Date()).format("YYYY-MM-DD");
      this._initData(currentDate);
    },
    data () {
      return {
        minHeight: window.innerHeight - 299
      }
    }
  });
}
