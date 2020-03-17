import { Mixins } from "../Mixins";
import { Util } from "../../../../common/utils";
import { _load_children, catchErr } from "../api/index";
Vue.component("Organization", {
  template: `
  <el-form-item label="考勤部门" prop="organizations">
    <el-cascader v-if="isCreated" style="width: 100%;" :props="props" :options="organizations"  v-model="formData.organizations"></el-cascader> 
    <el-cascader  v-else style="width:100%;" :props="propsOptions" :options= "organizations"  v-model="formData.organizations"></el-cascader>
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
      // console.log(node);
      const [err, data] = await catchErr(
        _load_children({ level: node.level + 1, parent_id })
      );
      data && resolve(data.orgs);
    },
  },
  data() {
    return {
      props: {
        lazy: true,
        multiple: true,
        value: "id",
        label: "name",
        lazyLoad: (node, resolve) => this._lazyLoad(node, resolve)
      },
      propsOptions: {
        lazy: false,
        multiple: true,
        value: "id",
        label: "name",
      },
      optionsValue: []
    };
  },
});
