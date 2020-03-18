import { Mixins } from "../Mixins";
Vue.component("WeekTimeHeader", {
  template: `
    <div v-if="data" class="week-time-header">
      <div class="time-ul" v-for="(item,index) in data" :key= "index">
          <div>{{item.date}}</div>
          <div>{{item.week_index}}</div>
      </div>
    </div>
  `,
  mixins: [Mixins],
  methods: {
   
  },
  data() {
    return {
     
    }
  },
});
