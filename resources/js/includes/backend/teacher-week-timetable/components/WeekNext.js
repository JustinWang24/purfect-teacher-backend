import { Mixins } from "../Mixins";
Vue.component("WeekNext", {
  template: `
  <div v-if="data" class="week-prev">
    <el-button type="info" @click="_next" icon="el-icon-arrow-right" circle></el-button>
  </div>
  `,
  mixins: [Mixins],
  methods: {
    _next() {
      const currentDate = moment(this.data.date).add('days',7).format("YYYY-MM-DD");
      this._initData(currentDate);
    }
  },
  data() {
    return {
     
    };
  }
});