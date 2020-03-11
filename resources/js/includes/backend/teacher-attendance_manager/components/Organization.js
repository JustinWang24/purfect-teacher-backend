import { Mixins } from "../Mixins";
import { Util } from "../../../../common/utils";
import {_load_children,catchErr} from "../api/index";
Vue.component("Organization", {
  template: `
  <el-form-item label="考勤部门" prop="organizations">
    <el-cascader style="width: 90%;" multiple :props="props" :options="organizations" v-model="formData.organizations" @change="_changeOrg"></el-cascader>
  </el-form-item>
  `,
  mixins: [Mixins],
  methods: {
    _add() {
      this.SETOPTIONS({ visibleFormDrawer: true });
    },
    async _lazyLoad(node, resolve) {
      let parent_id = null;
      if (!Util.isEmpty(node.data)) {
        parent_id = node.data.id;
      }
      const [err, data] = await catchErr(_load_children({level: node.level + 1,parent_id}));
      data && resolve(data.orgs)
    },
    _changeOrg (data) {
      console.log(data)
    }
  },
  data() {
    return {
      props: {
        lazy: true,
        multiple: true,
        value: "id",
        label: "name",
        lazyLoad: (node, resolve) => (this._lazyLoad(node, resolve))
      }
    };
  }
});
