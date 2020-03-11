import Vuex from "vuex";
const clockSetData = [
  "Monday",
  "Tuesday",
  "Wednesday",
  "Thursday",
  "Friday",
  "Saturday",
  "Sunday"
].map(week => ({
  id:'',
  week,
  start: "",
  end: "",
  morning: "",
  morning_late: "",
  afternoon: "",
  afternoon_late: "",
  evening: "",
  is_weekday: true
}));
export default new Vuex.Store({
  state: {
    data: {},
    isTableLoading: false,
    visibleFormDrawer: false,
    formData: {
      attendance: {
        id: "", //编辑时传递
        school_id: "",
        title: "",
        wifi_name: "",
        using_afternoon: true //是否启用中午打卡
      },
      organizations: []
    },
    organizations: [],
    isEditFormLoading: false,
    visibleClockDrawer: false,
    clockSetData,
    usingAfternoon:false,
    attendance_id:0
  },
  mutations: {
    SETOPTIONS(state, res) {
      Object.keys(res).forEach(item => (state[item] = res[item]));
    },
    SETOPTIONOBJ(state, { key, value }) {
      Object.keys(value).forEach(item => (state[key][item] = value[item]));
    }
  }
});
