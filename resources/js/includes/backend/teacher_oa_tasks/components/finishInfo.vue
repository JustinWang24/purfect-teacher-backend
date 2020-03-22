<template>
  <div class="infoList">
    <div class="info" v-for="(info,index) in infoList" :key="index">
      <div class="item">
        <span class="title">反馈人</span>
        <span class="content">
          <span class="name">{{info.username}}</span>
          <span class="status" :class="getStatusClass(info.status)">{{getFinishText(info.status)}}</span>
        </span>
      </div>
      <div class="item">
        <span class="title">备注说明</span>
        <span class="content">{{info.remark}}</span>
      </div>
      <div class="item">
        <span class="title">附件资料</span>
        <span class="content">
          <div
            class="img-box"
            @click="viewImg(img)"
            v-for="(img, index) in (info.user_pics?info.user_pics.split(','):[])"
            :key="index"
          >
            <img :src="img" alt />
          </div>
        </span>
      </div>
      <div class="item">
        <span class="title">反馈时间</span>
        <span class="content">{{info.update_time}}</span>
      </div>
    </div>
    <el-dialog :visible.sync="previewModal">
      <img width="100%" :src="previewSrc" alt />
    </el-dialog>
  </div>
</template>
<script>
import { TaskApi } from "../common/api";
import { getFileURL } from "../common/utils";
import { Util } from "../../../../common/utils";
import { TaskStatus } from "../common/enum";
export default {
  name: "finish-info",
  props: {
    taskid: {
      type: String,
      required: true
    }
  },
  computed: {
    getFinishText() {
      return function(status) {
        return (TaskStatus[status] || {}).text;
      };
    },
    getStatusClass() {
      return function(status) {
        return (TaskStatus[status] || {}).classes;
      };
    }
  },
  methods: {
    viewImg(src) {
      this.previewModal = true;
      this.previewSrc = src;
    }
  },
  data() {
    return {
      infoList: [{}],
      previewModal: false,
      previewSrc: ""
    };
  },
  created() {
    TaskApi.excute("getOaTaskReport", {
      taskid: this.taskid
    }).then(res => {
      this.infoList = res.data.data;
    });
  }
};
</script>
<style lang="scss" scoped>
.infoList {
  padding: 0 12px;
  .info:last-child {
    border: none;
  }
  .info {
    border-bottom: 1px solid #aaaaaa;
    .item:first-child {
      border: none;
    }
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
          cursor: pointer;
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
        .status {
          float: right;
        }
        .status.done {
          color: #6dcc58;
        }
        .status.timeout {
          color: #fd3434;
        }
        .status.pending {
          color: #fe7b1c;
        }
        .status.waiting {
          color: #fe7b1c;
        }
        .time {
          flex: 0;
          color: #eaedf2;
          font-size: 12px;
        }
      }
    }
  }
}
</style>
