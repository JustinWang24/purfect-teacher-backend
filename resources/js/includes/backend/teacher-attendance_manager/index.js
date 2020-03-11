// import { Util } from "../../../common/utils";
// import { Constants } from "../../../common/constants";

import store from "./store";
import { Mixins } from "./Mixins";
import AttendanceAdd from "./components/AttendanceAdd";
import AttendanceList from "./components/AttendanceList";
import AttendanceForm from "./components/AttendanceForm";
import AttendanceClockSet from "./components/AttendanceClockSet";
import "./index.css";
const teacherAttendanceManager = document.getElementById(
    "teacherAttendanceManager"
);
if (teacherAttendanceManager) {
    new Vue({
        mixins: [Mixins],
        store,
        el: "#teacherAttendanceManager",
        template: `
        <div class="attendance-manager-container">
          <AttendanceAdd />
          <AttendanceList />
          <AttendanceForm />
          <AttendanceClockSet />
        </div>`,
        created() {
            this._initData();
        }
    });
}
