<template>
  <div class="infoList">
    <div class="info" v-for="(info,index) in infoList" :key="index">
      <div class="item">
        <span class="title">反馈人</span>
        <span class="content">
          <span class="name"></span>
          <span class="status"></span>
        </span>
      </div>
      <div class="item">
        <span class="title">备注说明</span>
        <span class="content">content</span>
      </div>
      <div class="item">
        <span class="title">附件材料</span>
        <span class="content">
          <div class="img-box" v-for="(img, index) in (info.imgList||[])" :key="index">
            <img :src="img.src" alt />
          </div>
        </span>
      </div>
    </div>
  </div>
</template>
<script>
import { TaskApi } from "../common/api";
import { getFileURL } from "../common/utils";
import { Util } from "../../../../common/utils";
export default {
  name: "finish-info",
  props: {
    taskid: {
      type: String,
      required: true
    }
  },
  methods: {},
  data() {
    return {
      infoList: [{}]
    };
  },
  created() {
    TaskApi.excute("getOaTaskReport").then(res => {
      debugger;
    });
  }
};
</script>
<style lang="scss" scoped>
.infoList {
  padding: 0 12px;
  .info {
    .item {
      display: flex;
      border-top: 1px solid #eaedf2;
      padding-left: 22px;
      font-size: 14px;
      padding: 12px;
      .title {
        display: inline-block;
        width: 72px;
        color: #8a93a1;
      }
      .content {
        flex: 1;
        word-break: break-all;
        word-wrap: break-word;
        color: #313b4c;
        .img-box {
          display: inline-block;
          width: 120px;
          border-radius: 4px;
          margin: 0 12px 12px 0;
          height: 120px;
          border: 1px solid #aaaaaa;
          float: left;
          img {
            max-width: 100%;
            max-height: 100%;
          }
        }
      }
    }
  }
}
</style>
