<template>
  <div class="uploader-box">
    <div class="img-box item" v-for="(img, index) in imglist" :key="index">
      <img :src="img.src" alt />
      <i class="el-icon-error" @click="remove(index)"></i>
    </div>
    <div class="img-box img-add" v-if="imglist.length < 9">
      <label for="auto-form-img-upload" class="upload-desc">
        <i class="el-icon-plus"></i>
        <span>最多选择9张</span>
      </label>
      <input
        type="file"
        name="file"
        accept="image/gif, image/jpeg, image/jpg, image/png, image/svg"
        @change="onFileSelected"
        hidden
        multiple="multiple"
        id="auto-form-img-upload"
        class="el-upload__input"
      />
    </div>
  </div>
</template>
<script>
import { getFileURL } from "../common/util";
export default {
  name: "uploader",
  data() {
    return {
      imglist: []
    };
  },
  methods: {
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
    },
    remove(index) {
      this.imglist.splice(index, 1);
    }
  }
};
</script>
<style lang="scss" scoped>
.uploader-box {
  .img-box {
    display: inline-block;
    width: 120px;
    border-radius: 4px;
    margin: 0 12px 12px 0;
    height: 120px;
    border: 1px solid #aaaaaa;
    float: left;
    position: relative;
    img {
      max-width: 100%;
      max-height: 100%;
    }
    i.el-icon-error {
      position: absolute;
      right: -12px;
      top: -12px;
      font-size: 24px;
      color: #cccccc;
      cursor: pointer;
    }
  }
  .img-box.img-add {
    color: #aaaaaa;
    border: 1px dashed #aaaaaa;
    .upload-desc {
      cursor: pointer;
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
</style>