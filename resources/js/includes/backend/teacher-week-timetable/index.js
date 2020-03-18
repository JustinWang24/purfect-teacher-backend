// import { Util } from "../../../common/utils";
// import { Constants } from "../../../common/constants";

import store from "./store";
import { Mixins } from "./Mixins";
import WeekTimeTable from "./components/WeekTimeTable";
import WeekTimeHeader from "./components/WeekTimeHeader";

import "./index.css";
const teacherWeekTimetable = document.getElementById(
    "teacherWeekTimetable"
);
if (teacherWeekTimetable) {
    new Vue({
        mixins: [Mixins],
        store,
        el: "#teacherWeekTimetable",
        template: `
        <div class="teacher-week-timetable">
            <WeekTimeHeader />
            <WeekTimeTable />
        </div>`,
        created() {
            this._initData();
        }
    });
}
