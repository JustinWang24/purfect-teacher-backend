import { Mixins } from "../Mixins";
Vue.component("ManagerMent", {
  template: `
  <el-form-item label="管理员" prop="managers">
    <search-bar style="width: 100%;" :school-id="schoolIdx" full-tip="请输入教职工名字" scope="employee" :init-query="teacher" v-on:result-item-selected="_selectManager"></search-bar>
    <br/>
    <el-tag :key="idx" v-for="(item, idx) in formData.managers" closable :disable-transitions="false" @close="removeFromOrg(idx)">
        {{ item.name }}
    </el-tag>
  </el-form-item>
  `,
  mixins: [Mixins],
  computed: {
    schoolIdx() {
      return school_id;
    }
  },
  data () {
    return {
      teacher:''
    }
  },
  methods: {
    _selectManager(payload) {
      const { value, id } = payload.item;
      const { managers } = this.formData;
      const findIndex = managers.findIndex(item => item.id == id)
      if(findIndex !=-1) {
        this.$message.error('不能重复添加')
        return false
      }
      this.SETOPTIONOBJ({
        key: "formData",
        value: {
          managers: managers.concat({ id, name: value })
        }
      });
    },
    removeFromOrg(index) {
      this.formData.managers.splice(index, 1);
    }
  }
});
