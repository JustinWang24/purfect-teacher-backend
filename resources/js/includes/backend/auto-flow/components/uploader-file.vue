<template>
  <div class="uploader-box">
    <div class="file-box" v-for="(file, index) in filelist" :key="index">
      <div class="name">{{file.name}}</div>
      <div class="info">
        <span class="delete" @click="remove(index)">删除</span>
        <span class="size">{{converSize(file.size)}}</span>
      </div>
    </div>
    <div class="file-add" v-if="filelist.length < 9">
      <label for="auto-form-file-upload" class="upload-desc">
        <i class="el-icon-paperclip"></i>
        <span>添加附件</span>
      </label>
      <input
        type="file"
        name="file"
        @change="onFileSelected"
        hidden
        multiple="multiple"
        id="auto-form-file-upload"
        class="el-upload__input"
      />
    </div>
  </div>
</template>
<script>
import { converSize } from "../common/util";
export default {
  name: "uploader",
  data() {
    return {
      filelist: []
    };
  },
  computed: {
    converSize() {
      return function(size) {
        return converSize(size);
      };
    }
  },
  methods: {
    onFileSelected(e) {
      for (let index = 0; index < e.target.files.length; index++) {
        const file = e.target.files[index];
        this.filelist.push({
          name: file.name,
          size: file.size
        });
        this.files.push(file);
      }
    },
    remove(index) {
      this.filelist.splice(index, 1);
      this.files.splice(index, 1);
    }
  },
  created() {
    this.files = [];
  }
};
</script>
<style lang="scss" scoped>
.uploader-box {
  .file-box {
    display: flex;
    flex-direction: row;
    padding: 2px 12px;
    background: #f3f9ff;
    margin-bottom: 8px;
    .name {
      flex: 1;
      align-self: center;
      color: #666666;
      /* white-space: nowrap; */
      text-overflow: ellipsis;
      overflow: hidden;
      word-break: break-all;
      padding-right: 24px;
    }
    .info {
      flex: 0;
      text-align: right;
      .delete {
        cursor: pointer;
        color: #409eff;
        font-size: 14px;
      }
      .size {
        font-size: 12px;
        color: #cccccc;
      }
    }
  }
  .file-add {
    color: #409eff;
    .upload-desc {
      cursor: pointer;
    }
    i {
      font-size: 24px;
      float: left;
    }
  }
}
</style>