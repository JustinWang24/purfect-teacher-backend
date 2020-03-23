import { Mixins } from "../Mixins";
Vue.component("WeekTimeTable", {
  template: `
    <div class="week-time-table">
      <div v-for="(pItem,pIndex) in data.tableList" class="table-list">
        <template v-for="(item,index) in pItem">
        <div v-if="index == 0" class="table-item item-first">
           <div class="first-des"> 
           {{item.name}}
           <br />
           ({{item.from}}-{{item.to}})
           </div>
        </div>
        <template v-else>
          <div v-if="item.name" class="table-item">
            <div class="item-content">
            <div class="item-top" :style="{'background': _getColor(item,'backAndColors')}"><span class="optional">{{item.optional ? '选修课':'必修课'}}</span><span>{{repeatUnits[item.repeat_unit]}}</span></div>
            <div class="item-bottom" :style="{'color': _getColor(item,'backAndColors'),'background':_getColor(item,'bottomBack')}">
              <p class="des-info"><span class="iconfont">&#xe7ed;</span>{{item.switching ? item.old_course: item.name }}</p>
              <p class="des-info">
                <span class="iconfont">&#xe663;</span>
                <span class="info-grade">
                  <span v-for="(grade,gradeIndex) in item.grade" :key= "gradeIndex">{{grade.grade_name}}</span>
                </span>
              </p>
              <p class="des-info">
                <span class="iconfont">&#xe644;</span>
                {{item.room}}
              </p>
            </div>
            </div>
          </div>
          <div v-else class="table-item" />
        </template>
        </template>
      </div>
    </div>
  `,
  mixins: [Mixins],
  methods: {
    _getColor(item,name) {
      //"repeat_unit": 1,  // 1每周重复 2每单周重复 3每双周重复
      // "optional": true, //true选修课 // false必修课 
      return item.switching ? this[name][4]:item.optional ? this[name][0] :this[name][parseInt(item.repeat_unit)]
    },
  },
  data() {
    return {
      minHeight: window.innerHeight - 299,
      repeatUnits:['','每周重复','单周重复','双周重复'],
      // 选修课 必修每周重复 必修单周重复 必修双周重复 调课临时课
      backAndColors:['#FFBD1BFF','#4ACB11FF','#1D84F8FF','#AE59F5FF','#FE7490FF'],
      bottomBack: ['#F8EBC7FF','#D2EDC7FF','#BEE7FFFF','#F3E7FFFF','#FFE5EAFF']
    };
  }
});


