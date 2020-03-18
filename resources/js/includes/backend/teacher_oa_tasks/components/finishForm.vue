<template>
  <div class="form">
    <el-form ref="form" :model="form" label-width="80px">
      <el-input type="textarea" v-model="form.content" rows="5" placeholder="请输入完成结果"></el-input>
      <p class="upload-title">附件资料</p>
      <div class="uploader-box">
        <div class="img-box item" v-for="(img, index) in imglist" :key="index">
          <img :src="img.src" alt />
        </div>
        <div class="img-box img-add" v-if="imglist.length < 9">
          <label for="task-finish-upload" class="upload-desc">
            <i class="el-icon-plus"></i>
            <span>最多9张</span>
            <span>（选填）</span>
          </label>
          <input
            type="file"
            name="file"
            accept="image/gif, image/jpeg, image/jpg, image/png, image/svg"
            @change="onFileSelected"
            hidden
            multiple="multiple"
            id="task-finish-upload"
            class="el-upload__input"
          />
        </div>
      </div>
    </el-form>
    <div class="btn-box">
      <el-button type="primary" @click="onSubmit">确定</el-button>
    </div>
  </div>
</template>
<script>
import { TaskApi } from "../common/api";
import { getFileURL } from "../common/utils";
export default {
  name: "comment-form",
  props: {
    taskid: {
      type: String,
      required: true
    }
  },
  methods: {
    onSubmit() {
      if (!this.form.content) {
        this.$message.error("请输入完成结果");
        return;
      }
      TaskApi.excute("finishOaTaskInfo", {
        taskid: this.taskid,
        remark: this.form.content,
        pics: this.imglist.map(img => {
          return img.file;
        })
      }).then(res => {
        this.$emit("reply");
      });
    },
    onFileSelected(e) {
      if (e.target.files.length + this.imglist.length > 9) {
        this.$message.error("最多上传9张图");
      } else {
        for (let index = 0; index < e.target.files.length; index++) {
          const file = e.target.files[index];
          this.imglist.push({
            src: getFileURL(file),
            file
          });
        }
      }
      var file = document.getElementById("task-finish-upload");
    }
  },
  data() {
    return {
      form: {},
      imglist: []
    };
  }
};
</script>
<style lang="scss" scoped>
.form {
  display: flex;
  height: 100%;
  flex-direction: column;
  .el-form {
    flex: 1;
    padding: 12px;
    .upload-title {
      font-weight: bold;
      margin: 16px 0;
      font-size: 20px;
      color: #333333;
    }
    .uploader-box {
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
      .img-box.img-add {
        color: #aaaaaa;
        cursor: pointer;
        .upload-desc {
          font-size: 12px;
          display: flex;
          flex-direction: column;
          align-items: center;
          height: 100%;
          justify-content: center;
          margin: 0 !important;
          i {
            font-size: 38px;
          }
        }
      }
    }
  }
  .btn-box {
    flex: none;
    padding: 12px;
    text-align: right;
  }
}
</style>
