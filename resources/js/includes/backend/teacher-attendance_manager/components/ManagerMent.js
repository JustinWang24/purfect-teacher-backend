import { Mixins } from "../Mixins";
Vue.component("ManagerMent", {
  template: `
  <el-form-item label="管理员" prop="managers">
    <search-bar :school-id="schoolIdx" full-tip="输入教职工名字" scope="employee" :init-query="teacher" v-on:result-item-selected="_selectManager"></search-bar>
    <el-tag :key="idx" v-for="(member, idx) in members" closable :disable-transitions="false" @close="removeFromOrg(idx)">
        @{{ member }}
    </el-tag>
  </el-form-item>
  `,
  mixins: [Mixins],
  data() {
    return  {
      members :[],
      teacher:''
    }
  },
  computed : {
    schoolIdx () {
      return school_id
    }
  },
  methods: {
    _selectManager (payload) {
      const {value,id} = payload.item
       const {managers} = this.formData
       this.SETOPTIONOBJ({key:'formData',value: {
        managers:[...managers,id]
       }})
    },
    removeFromOrg (index) {
      this.members.splice(index, 1);
      this.formData.managers.splice(index, 1);
    }
  }
})