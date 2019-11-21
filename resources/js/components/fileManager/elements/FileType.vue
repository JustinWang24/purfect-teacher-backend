<template>
  <div>
    <div class="file">
      <img class="img" v-if="isGeneral(fileDic.type)" src="/assets/img/mega-img1.jpg" alt="图片">
      <img class="img" v-if="isImage(fileDic.type)" :src="fileDic.url" alt="图片加载失败">
      <div class="el-card-content" v-if="isOffice(fileDic.type)">
          <p class="message">{{fileDic.file_name}}</p>
          <p class="message-desc">{{fileDic.created_at}}&nbsp;{{ fileSize(fileDic.size) }}</p>
          <p class="message-desc">简介: {{fileDic.description}}</p>
      </div>
      <div class="el-card-content" v-if="isPdf(fileDic.type)">
        <a class="message" :href="fileDic.url">文件名: {{fileDic.file_name}}</a>
        <p class="message">创建时间: {{fileDic.created_at}}</p>
        <p class="message">大小: {{ fileSize(fileDic.size) }}</p>
        <p class="message">简介: {{fileDic.description}}</p>
      </div>
      <video class="video" v-if="isVideo(fileDic.type)" controls>
        <source :src="fileDic.url" type="video/mp4">
        您的浏览器不支持 video 标签。
      </video >
      <audio class="audio" v-if="isAudio(fileDic.type)"  controls>
        <source :src="fileDic.url" type="audio/mpeg">
        您的浏览器不支持 audio 元素。
      </audio>
    </div>
  </div>
</template>

<script>
  import {Util} from '../../../common/utils';
  import {Constants} from '../../../common/constants';
  export default {
    name: 'FileType',
    props: {
      fileDic: Object,defaultType: Number
    },
    methods: {
        isImage: function(type){
            return Util.isImage(type) || Constants.FILE_TYPE.IMAGE === this.defaultType;
        },
        isGeneral: function(type){
            return Util.isGeneralDoc(type);
        },
        isOffice: function(type){
            return Util.isWordDoc(type) || Util.isExcelDoc(type) || Util.isPowerPointDoc(type) || Util.isTxtDoc();
        },
        isPdf: function(type){
            return Util.isPdfDoc(type);
        },
        isVideo: function(type){
            return Util.isVideoDoc(type);
        },
        isAudio: function(type){
            return Util.isAudioDoc(type);
        },
        fileSize: function(size){
            return Util.fileSize(size);
        },
    }
}
</script>

<style lang="scss" scoped>
  .file {
    display: flex;
    width: 100%;
    .img {
      position: relative;
      width: 100%;
      height: 100%;
    }
    .video {
      position: relative;
      width: 100%;
      height: 100%;
    }
    .video {
      position: relative;
      width: 100%;
      height: 100%;
    }
    .audio {
      position: relative;
      width: 100%;
      height: 100%;
    }
    .el-card-content {
      color: #303133;
      -webkit-transition: .3s;
      transition: .3s;
      p {
        padding-top: 3px;
        line-height: 20px;
        font-size: 13px;
        margin: 0;
      }
      .message-desc{
        font-size: 11px;
        color: #cccbcb;
        line-height: 18px;
      }
    }
  }
</style>
