import { mapState, mapActions, mapMutations } from "vuex";
import { _timetable_teacher_week, catchErr } from "./api/index";

export const Mixins = {
  computed: {
    ...mapState(["data", "isTableLoading"])
  },
  methods: {
    ...mapMutations(["SETOPTIONS", "SETOPTIONOBJ"]),
    async _initData(currentDate) {
      this.SETOPTIONS({ isTableLoading: true });
      let [err, data] = await catchErr(_timetable_teacher_week({ date:currentDate }));
      const {timetable,time_slots,week_index,date} = data
      let tableHeader = timetable.map(({table})=>table)
      tableHeader.unshift({title:'周课'})
      let tableList =  []
      for (let index = 0; index < time_slots.length; index++) {
        const rowFirst = time_slots[index]
        const coureList = timetable.map(item => {
          return item['course'][index]
        })
        coureList.unshift(rowFirst)
        tableList.push({...coureList})
      }
      data && this.SETOPTIONS({ data:{date,week_index,tableList,tableHeader}, isTableLoading: false });
    }
  }
};
