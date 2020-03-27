<template>
  <div class="uploader-box">
    <div
      class="img-box item"
      v-for="(img, index) in imglist"
      :key="index"
      v-loading="img.loading"
      element-loading-spinner="el-icon-loading"
      element-loading-background="rgba(0, 0, 0, 0.8)"
    >
      <img :src="img.src" alt />
      <i class="el-icon-error" @click="remove(index)" v-if="!img.loading"></i>
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
        ref="referenceUpload"
        multiple="multiple"
        id="auto-form-img-upload"
        class="el-upload__input"
      />
    </div>
  </div>
</template>
<script>
import { getFileURL } from "../common/util";
import { Util } from "../../../../common/utils";
export default {
  name: "uploader",
  data() {
    return {
      imglist: [],
      imgCounts: 0
    };
  },
  watch: {
    imglist: {
      deep: true,
      immediate: true,
      handler(list) {
        this.$emit(
          "input",
          (() => {
            let res = [];
            list.forEach(img => {
              if (img.url) {
                res.push(img.url);
              }
            });
            return res;
          })().toString()
        );
      }
    }
  },
  methods: {
    onFileSelected(e) {
      if (e.target.files.length + this.imglist.length > 9) {
        this.$message.error("最多上传9张图");
      } else {
        for (let index = 0; index < e.target.files.length; index++) {
          let _index = this.imgCounts;
          const file = e.target.files[index];
          this.imglist.push({
            src: getFileURL(file),
            loading: true,
            index: _index
          });
          this.uploadImg(file, _index);
          this.imgCounts = this.imgCounts + 1;
        }
      }
      this.$refs.referenceUpload.value = null;
    },
    uploadImg(file, index) {
      const form = new FormData();
      form.append("file[0]", file);
      window
        .axios({
          method: "post",
          url: "/api/Oa/message-upload-files",
          headers: {
            "Content-Type": "multipart/form-data;charset=UTF-8"
          },
          data: form
        })
        .then(res => {
          if (Util.isAjaxResOk(res)) {
            this.imglist[index].loading = false;
            this.imglist[index].url = res.data.data.imgPath[0].path;
          }
        });
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
    display: flex;
    width: 120px;
    border-radius: 4px;
    text-align: center;
    margin: 0 12px 12px 0;
    height: 120px;
    border: 1px solid #aaaaaa;
    float: left;
    position: relative;
    align-items: center;
    justify-content: center;
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