<template>
  <div class="auto-flow-container">
    <div class="forms">
      <template v-for="(form, index) in formList">
        <template v-if="form.type === 'date-date'">
          <base-form :key="index" :type="form.type" :props="form" v-model="form.value"/>
        </template>
        <template v-if="form.type === 'node'">
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
        <template v-if="form.type !== 'node' && form.type !== 'date-date'">
          <div class="form-item" :key="index">
            <div class="title">
              <span class="required" v-if="form.required">*</span>
              <span>{{form.title}}</span>
            </div>
            <div class="content">
              <base-form :type="form.type" :props="form" :uuid="user.uuid" v-model="form.value"/>
            </div>
          </div>
        </template>
      </template>
      <el-divider></el-divider>
      <div class="flow-info handlers" v-if="handlers.length>0">
        <div class="title">
          <span>审批人</span>
        </div>
        <div class="flow-timeline">
          <timeline>
            <timeline-item v-for="(handleLevel, index) in handlers" :key="index">
              <div v-for="(peo,pindex) in handleLevel" :key="pindex" class="handler-level">
                <div
                  class="handler item"
                  v-if="!peo.avatar"
                >{{peo.name.length>2?peo.name.substring(peo.name.length-2,peo.name.length):peo.name}}</div>
                <Avatar class="handler avatar" v-else :src="peo.avatar" />
                <span class="name">{{peo.name}}</span>
              </div>
            </timeline-item>
          </timeline>
        </div>
      </div>
      <div class="flow-info copys" v-if="copys.length>0">
        <div class="title">
          <span>抄送人（{{copys.length}}人）</span>
        </div>
        <div class="copy-list">
          <div class="copy-item" v-for="(peo, index) in copys" :key="index">
            <div
              class="item"
            >{{peo.name.length>2?peo.name.substring(peo.name.length-2,peo.name.length):peo.name}}</div>
            <span class="name">{{peo.name}}</span>
          </div>
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
import { Timeline, TimelineItem } from "element-ui";
import Avatar from "./avatar";

export default {
  name: "auto-form",
  props: {
    id: {
      required: true
    }
  },
  components: {
    BaseForm,
    Timeline,
    TimelineItem,
    Avatar
  },
  data() {
    return {
      formList: [],
      copys: [],
      handlers: [],
      user: {}
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
            this.user = data.user;
            this.formList = data.options;
            this.copys = data.copys;
            this.handlers = data.handlers.map(handleLevel => {
              let list = [];
              Object.keys(handleLevel).forEach(levelKey => {
                list = list.concat(handleLevel[levelKey]);
              });
              return list;
            });
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
    box-shadow: 0 0 8px #dddddd;
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
      .flow-timeline {
        margin: 12px 0 12px 32px;
      }
      .title {
        font-weight: bold;
        margin-left: 12px;
      }
      .handler-level {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
        .name {
          margin-left: 12px;
        }
      }
      .handler.item {
        border-radius: 50%;
        display: inline-flex;
        width: 50px;
        height: 50px;
        color: #ffffff;
        font-size: 14px;
        align-items: center;
        justify-content: center;
        word-break: keep-all;
        background-color: #72a5f8;
        .name {
          color: #666666;
        }
      }
      .handler.avatar {
        display: inline-block;
      }
      .copy-list {
        margin-left: 56px;
        .copy-item {
          align-items: center;
          display: inline-flex;
          flex-direction: column;
          margin: 10px 10px 0px 0;
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
            word-break: keep-all;
            background-color: #72a5f8;
          }
          .name {
            font-size: 12px;
            color: #666666;
          }
        }
      }
    }
  }
}
</style>