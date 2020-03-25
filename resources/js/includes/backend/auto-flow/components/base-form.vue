<template>
  <div class="auto-flow-base-form">
    <el-input
      type="text"
      maxlength="20"
      v-if="type==='input'"
      v-model="data"
      :placeholder="props.tips"
    ></el-input>
    <el-input
      type="textarea"
      maxlength="200"
      v-if="type==='textarea'"
      v-model="data"
      :placeholder="props.tips"
    ></el-input>
    <number-input
      type="textarea"
      maxlength="20"
      v-if="type==='number'"
      v-model="data"
      :decimalLen="(props.extra&&props.extra.floats)?2:0"
      :placeholder="props.tips"
    ></number-input>
    <el-checkbox-group v-if="type==='checkbox'" v-model="checkData" @change="onCheckChange">
      <el-checkbox
        v-for="(option, index) in props.extra.itemList"
        :key="index"
        :label="index"
      >{{option.itemText}}</el-checkbox>
    </el-checkbox-group>
    <el-radio-group v-if="type==='radio'" v-model="data">
      <el-radio
        v-for="(option, index) in props.extra.itemList"
        :label="index"
        :key="index"
      >{{option.itemText}}</el-radio>
    </el-radio-group>
    <!-- props.extra.dateType==1?'datetime':'date' -->
    <el-date-picker
      v-model="data"
      v-if="type==='date'"
      align="right"
      :type="props.extra.dateType==1?'datetime':'date'"
      :format="props.extra.dateType==1?'yyyy-MM-dd HH:mm':'yyyy-MM-dd'"
      :placeholder="props.tips"
    ></el-date-picker>
    <el-date-picker
      v-model="data"
      v-if="type==='date-date'"
      align="right"
      :type="props.extra.dateType==1?'datetime':'date'"
      :format="props.extra.dateType==1?'yyyy-MM-dd HH:mm':'yyyy-MM-dd'"
      :placeholder="props.tips"
    ></el-date-picker>
    <UploaderImg v-if="type==='image'" />
    <number-input
      type="textarea"
      maxlength="20"
      v-if="type==='money'"
      v-model="data"
      :decimalLen="2"
      :placeholder="props.tips"
    ></number-input>
    <UploaderFile v-if="type==='files'" />
    <el-cascader
      popper-class="auto-flow-form"
      v-if="type==='department'"
      :props="orgProps"
      v-model="data"
    ></el-cascader>
    <el-cascader
      popper-class="auto-flow-form"
      v-if="type==='area'"
      :options="cities"
      filterable
      :props="{
        label:'name',
        value:'id'
      }"
      v-model="data"
    ></el-cascader>
  </div>
</template>
<script>
import NumberInput from "./number-input";
import UploaderImg from "./uploader-img";
import UploaderFile from "./uploader-file";
import { Util } from "../../../../common/utils";
import cities from "../common/cityies";

export default {
  name: "BaseForm",
  components: {
    NumberInput,
    UploaderImg,
    UploaderFile
  },
  props: {
    type: {
      /**
        input
        textarea: "",
        number: "",
        radio: "",
        checkbox: [],
        date: null,
        "date-date": null,
        image: [],
        files: [],
        area: {},
        //   node //文字说明 不渲染
        department: []
        */
      type: String,
      required: true
    },
    props: {
      type: Object,
      default: Object
    }
  },
  data() {
    return {
      cities: cities,
      data: "",
      checkData: [],
      orgProps: {
        lazy: true,
        multiple: true,
        value: "id",
        label: "name",
        lazyLoad(node, resolve) {
          let parentId = null;
          if (!Util.isEmpty(node.data)) {
            parentId = node.data.id;
          }
          axios
            .post("/Oa/tissue/getOrganization", {
              parent_id: parentId,
              type: 1
            })
            .then(res => {
              if (Util.isAjaxResOk(res)) {
                resolve(res.data.data.organ);
              }
            });
        }
      }
    };
  },
  methods: {
    onCheckChange(res) {
      this.$emit(
        "input",
        res.map(index => {
          return this.props.extra.itemList[index];
        })
      );
    }
  }
};
</script>
<style lang="scss" scoped>
.el-input {
  width: 100% !important;
}
.el-cascader {
  width: 100% !important;
}
</style>
<style lang="scss">
.auto-flow-form {
  label.el-checkbox {
    margin: 0 !important;
  }
}
</style>