<template>
  <div class="auto-flow-container">
    <div class="forms">
      <template v-for="(form, index) in formList">
        <template v-if="form.type === 'date-date'">
          <div class="form-item" :key="index+'_1'">
            <div class="title">
              <span class="required" v-if="form.required">*</span>
              <span>{{form.title}}</span>
            </div>
            <div class="content">
              <base-form :type="form.type" :props="form" />
            </div>
          </div>
          <div class="form-item" :key="index+'_2'">
            <div class="title">
              <span class="required" v-if="form.required">*</span>
              <span>{{form.extra.title2}}</span>
            </div>
            <div class="content">
              <base-form :type="form.type" :props="form" />
            </div>
          </div>
        </template>
        <template v-if="form.type == 'node'">
          <div class="form-item" :key="index" style="padding: 12px 0">
            <div class="title">
              <span class="required" v-if="form.required">*</span>
              <span>{{form.name}}</span>
            </div>
            <div class="content">
              <span class="node">{{form.title}}</span>
            </div>
          </div>
        </template>
        <template v-else>
          <div class="form-item" :key="index">
            <div class="title">
              <span class="required" v-if="form.required">*</span>
              <span>{{form.title}}</span>
            </div>
            <div class="content">
              <base-form :type="form.type" :props="form" />
            </div>
          </div>
        </template>
      </template>
      <el-divider></el-divider>
      <div class="flow-info copys" v-if="copys.length>0">
        <div class="title">
          <span>抄送人（{{copys.length}}人）</span>
        </div>
        <div class="copy-list">
          <div
            class="item"
            v-for="(peo, index) in copys"
            :key="index"
          >{{peo.name.length>2?peo.name.substring(peo.name.length-2,peo.name.length):peo.name}}</div>
        </div>
      </div>
    </div>
    <div class="submit-btn">
      <el-button type="primary" @click="onSubmit">立即创建</el-button>
    </div>
  </div>
</template>
<script>
import { Util } from "../../../../common/utils";
import BaseForm from "./base-form";

export default {
  name: "auto-form",
  props: {
    id: {
      required: true
    }
  },
  components: {
    BaseForm
  },
  data() {
    return {
      formList: [],
      copys: []
    };
  },
  methods: {
    initData() {
      const url = "/api/pipeline/flow/open";
      axios
        .post(url, {
          flow_id: this.id
        })
        .then(res => {
          if (Util.isAjaxResOk(res)) {
            let data = res.data.data;
            this.formList = data.options;
            debugger;
            this.copys = data.copys;
            this.handlers = data.handlers;
          }
        })
        .catch(err => {
          this.$message({
            message: "获取表单信息失败",
            type: "warning"
          });
        });
    },
    onSubmit() {}
  },
  created() {
    this.initData();
  }
};
</script>
<style lang="scss" scoped>
.auto-flow-container {
  display: flex;
  flex-direction: column;
  height: 100%;
  .submit-btn {
    flex: none;
    text-align: center;
    padding: 12px 0;
    box-shadow: 0 0 23px #cccccc;
  }
  .forms {
    flex: 1;
    overflow-y: auto;
    padding: 12px;
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
    .flow-info {
      .title {
        font-weight: bold;
        margin-left: 12px;
      }
      .copy-list {
        margin-left: 56px;
        .item {
          border-radius: 50%;
          display: flex;
          width: 50px;
          float: left;
          height: 50px;
          color: #ffffff;
          font-size: 14px;
          align-items: center;
          justify-content: center;
          margin: 10px 10px 0px 0;
          word-break: keep-all;
          background-color: #72a5f8;
        }
      }
    }
  }
}
</style>