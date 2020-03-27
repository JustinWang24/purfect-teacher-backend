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
      type="text"
      maxlength="20"
      v-if="type==='number'"
      v-model="data"
      :decimalLen="(props.extra&&props.extra.floats)?2:0"
      :placeholder="props.tips"
    ></number-input>
    <el-checkbox-group v-if="type==='checkbox'" v-model="checkData">
      <el-checkbox
        v-for="(option, index) in props.extra.itemList"
        :key="index"
        :label="option.itemText"
      >{{option.itemText}}</el-checkbox>
    </el-checkbox-group>
    <el-radio-group v-if="type==='radio'" v-model="data">
      <el-radio
        v-for="(option, index) in props.extra.itemList"
        :label="option"
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
      :value-format="props.extra.dateType==1?'yyyy-MM-dd HH:mm':'yyyy-MM-dd'"
      :placeholder="props.tips"
    ></el-date-picker>
    <UploaderImg v-if="type==='image'" v-model="data" />
    <number-input
      type="textarea"
      maxlength="20"
      v-if="type==='money'"
      v-model="data"
      :decimalLen="2"
      :placeholder="props.tips"
    ></number-input>
    <UploaderFile v-if="type==='files'" :uuid="uuid" v-model="data" />
    <el-cascader
      popper-class="auto-flow-form"
      v-if="type==='department'"
      :props="orgProps"
      ref="department"
      v-model="departmentData"
      separator="/"
    ></el-cascader>
    <el-cascader
      popper-class="auto-flow-form"
      v-if="type==='area'"
      :options="cities"
      filterable
      ref="areaSelector"
      separator="/"
      :props="{
        label:'name',
        value:'id'
      }"
      v-model="areaData"
    ></el-cascader>
    <template v-if="type==='date-date'">
      <div class="form-item">
        <div class="title">
          <span class="required" v-if="props.required">*</span>
          <span>{{props.title}}</span>
        </div>
        <div class="content">
          <el-date-picker
            v-model="start"
            align="right"
            :type="props.extra.dateType==1?'datetime':'date'"
            :format="props.extra.dateType==1?'yyyy-MM-dd HH:mm':'yyyy-MM-dd'"
            :value-format="props.extra.dateType==1?'yyyy-MM-dd HH:mm':'yyyy-MM-dd'"
            :placeholder="props.tips"
            :picker-options="pickerOptionStart"
          ></el-date-picker>
        </div>
      </div>
      <div class="form-item">
        <div class="title">
          <span class="required" v-if="props.required">*</span>
          <span>{{props.extra.title2}}</span>
        </div>
        <div class="content">
          <el-date-picker
            v-model="end"
            align="right"
            :type="props.extra.dateType==1?'datetime':'date'"
            :format="props.extra.dateType==1?'yyyy-MM-dd HH:mm':'yyyy-MM-dd'"
            :value-format="props.extra.dateType==1?'yyyy-MM-dd HH:mm':'yyyy-MM-dd'"
            :placeholder="props.tips"
            :picker-options="pickerOptionEnd"
          ></el-date-picker>
        </div>
      </div>
    </template>
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
    uuid: {
      type: String,
      default: ""
    },
    props: {
      type: Object,
      default: Object
    }
  },
  watch: {
    data: function(val) {
      this.$emit("input", val);
    },
    checkData: {
      deep: true,
      immediate: true,
      handler(value) {
        this.$emit("input", value.toString());
      }
    },
    departmentData: {
      deep: true,
      immediate: true,
      handler(value) {
        setTimeout(() => {
          if(!this.$refs.department){
            return
          }
          this.$emit(
            "input",
            this.$refs.department.presentTags
              .map(tag => {
                return tag.text;
              })
              .toString()
          );
        });
      }
    },
    areaData: function(val) {
      setTimeout(() => {
        this.$emit("input", this.$refs.areaSelector.inputValue);
      }, 50);
    },
    start: function(val) {
      this.$emit("input", this.start + " ~ " + this.end);
    },
    end: function(val) {
      this.$emit("input", this.start + " ~ " + this.end);
    }
  },
  data() {
    return {
      cities: cities,
      data: "",
      checkData: [],
      areaData: [],
      departmentData: [],
      start: "",
      end: "",
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
      },
      pickerOptionStart: (() => {
        let that = this;
        return {
          disabledDate(time) {
            if (that.end) {
              return time.getTime() > new Date(that.end);
            }
            return false;
          }
        };
      })(),
      pickerOptionEnd: (() => {
        let that = this;
        return {
          disabledDate(time) {
            if (that.start) {
              return time.getTime() < new Date(that.start);
            }
            return false;
          }
        };
      })()
    };
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

.form-item {
  display: flex;
  margin-bottom: 12px;
  .title {
    width: 100px;
    position: relative;
    margin-left: 12px;
    .required {
      color: red;
      position: absolute;
      left: -12px;
    }
  }
  .content {
    flex: 1;
    .node {
      color: #409eff;
    }
  }
}
</style>
<style lang="scss">
.auto-flow-form {
  label.el-checkbox {
    margin-bottom: 0 !important;
  }
}
</style>