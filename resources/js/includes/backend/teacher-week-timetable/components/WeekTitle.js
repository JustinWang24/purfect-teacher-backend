import { Mixins } from "../Mixins";
Vue.component("WeekTitle", {
  template: `
  <div v-if="data" class="week-title">
    <span>{{data.date}}</span> <span>{{data.week_index}}</span>
  </div>
  `,
  mixins: [Mixins],
  methods: {},
  data() {
    return {
     
    };
  }
});
