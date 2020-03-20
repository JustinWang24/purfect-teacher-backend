import { Mixins } from "../Mixins";
Vue.component("WeekPrev", {
  template: `
  <div v-if="data" class="week-prev">
    <el-button type="info" @click="_prev" icon="el-icon-arrow-left" circle></el-button>
  </div>
  `,
  mixins: [Mixins],
  methods: {
    _prev () {
      const currentDate = moment(this.data.date).add('days',-7).format("YYYY-MM-DD");
      this._initData(currentDate);
    }
  },
});