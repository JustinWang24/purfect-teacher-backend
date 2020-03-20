import { Mixins } from "../Mixins";
Vue.component("WeekTimeHeader", {
  template: `
    <div v-if="data" class="week-time-header">
      <div class="time-ul" :style="{'background':_contrastCurrentDate(item)}" v-for="(item,index) in data.tableHeader" :key= "index">
        <template v-if="index == 0">
          <div class="header-index-r">星期</div>
          <div class="header-index-l">课节</div>
        </template>
        <template v-else>
          <div>{{item.week_index}}</div>
          <div>{{item.date}}</div>
        </template>
      </div>
    </div>
  `,
  mixins: [Mixins],
  methods: {
    _contrastCurrentDate (item) {
      const constDate = this.data.date.slice(0,4) + '年' +  item.date
      const currentDate = moment(new Date()).format('YYYY年MM月DD日')
      return constDate == currentDate ? '#aacaf7' : '#eef1fb'
    }
  },
});
