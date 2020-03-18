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
      let tableHeader = data.timetable.map(({table})=>table)
      tableHeader.unshift({title:'周课'})
      let tableList =  []
      for (let index = 0; index < data.time_slots.length; index++) {
        const rowFirst = [data.time_slots[index]]  
        const coureList = data.timetable.map(item => {
          return item['course'][index]
        })
        tableList.push(rowFirst.concat(coureList))
      }
      console.log(tableHeader)
      console.log(tableList)
      data && this.SETOPTIONS({ data:{tableList,tableHeader}, isTableLoading: false });
    }
  }
};
