import { mapState, mapActions, mapMutations } from "vuex";
import { _timetable_teacher_week, catchErr } from "./api/index";

export const Mixins = {
  computed: {
    ...mapState(["data", "isTableLoading"])
  },
  methods: {
    ...mapMutations(["SETOPTIONS", "SETOPTIONOBJ"]),
    async _initData() {
      this.SETOPTIONS({ isTableLoading: true });
      const date = moment(new Date()).format("YYYY-MM-DD");
      let [err, data] = await catchErr(_timetable_teacher_week({ date }));
      let obj = {
        time_table_id: 314, // 课程表ID
        time_slot_id: 6, // 课节ID
        grade_name: "17电子班", // 班级名称
        idx: "", // 课程第几次
        room: "101", // 地点
        course: "语文", // 课程
        time_slot_name: "第一节课", // 课节
        from: "08:00:00", // 开始时间
        to: "08:45:00", // 结束时间
        label: [
          // 标签
          "课中课件",
          "课前预习"
        ]
      };
      data[0]["timetable"].push(obj);
      data[1]["timetable"].push(obj);
      data[2]["timetable"].push(obj);
      data[3]["timetable"].push(obj);
      data[4]["timetable"].push(obj);
      data[5]["timetable"].push(obj);
      data[6]["timetable"].push(obj);
      // 取到timetable的第index个
      const timetableLength = data[0]['timetable'].length
      let tableData = [];
      for (let index = 0; index < timetableLength; index++) {
        let timetable = data.map(({timetable}) => (timetable[index]))
        timetable.unshift(timetable[0])
        tableData.push({[index]:timetable})
      }
      data && this.SETOPTIONS({ data, isTableLoading: false });
    }
  }
};
