<template>
  <div>
    <div class="file">
      <img class="img" v-if="isGeneral(fileDic.type)" src="/assets/img/mega-img1.jpg" alt="图片">
      <img class="img" v-if="isImage(fileDic.type)" :src="fileDic.url" alt="图片加载失败">
      <div class="el-card-content" v-if="isOffice(fileDic.type)">
          <p class="message">
              <i v-if="isExcel(fileDic.type)" class="fa fa-file-excel-o"></i>
              <i v-if="isPpt(fileDic.type)" class="fa fa-file-powerpoint-o"></i>
              <i v-if="isWord(fileDic.type)" class="fa fa-file-word-o"></i>
              {{fileDic.file_name}}
          </p>
          <p class="message-desc">{{fileDic.created_at}}&nbsp;{{ fileSize(fileDic.size) }}</p>
          <p class="message-desc">简介: {{fileDic.description}}</p>
      </div>
      <div class="el-card-content" v-if="isTxt(fileDic.type)">
          <p class="message">
              <i class="fa fa-file-text-o"></i>&nbsp;
              {{fileDic.file_name}}
          </p>
          <p class="message-desc">{{fileDic.created_at}}&nbsp;{{ fileSize(fileDic.size) }}</p>
          <p class="message-desc">简介: {{fileDic.description}}</p>
      </div>
      <div class="el-card-content" v-if="isPdf(fileDic.type)">
        <a class="message" :href="fileDic.url" target="_blank"><i v-if="isPdf(fileDic.type)" class="fa fa-file-pdf-o"></i> {{fileDic.file_name}}</a>
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
  export default {
    name: 'FileType',
    props: {
      fileDic: Object,defaultType: Number
    },
    methods: {
        isImage: function(type){
            return Util.isImage(type) || Util.isImage(this.defaultType);
        },
        isGeneral: function(type){
            return Util.isGeneralDoc(type)  || Util.isGeneralDoc(this.defaultType);
        },
        isOffice: function(type){
            if(Util.isEmpty(type)){
                type = this.defaultType;
            }
            return Util.isWordDoc(type) || Util.isExcelDoc(type) || Util.isPowerPointDoc(type) || Util.isTxtDoc();
        },
        isWord: function(type){
            return Util.isWordDoc(type) || Util.isWordDoc(this.defaultType);
        },
        isExcel: function(type){
            return Util.isExcelDoc(type) || Util.isExcelDoc(this.defaultType);
        },
        isPpt: function(type){
            return Util.isPowerPointDoc(type) || Util.isPowerPointDoc(this.defaultType);
        },
        isTxt: function(type){
            return Util.isTxtDoc(type) || Util.isTxtDoc(this.defaultType);
        },
        isZip: function(type){
            return false;
        },
        isPdf: function(type){
            return Util.isPdfDoc(type) || Util.isPdfDoc(this.defaultType);
        },
        isVideo: function(type){
            return Util.isVideoDoc(type) || Util.isVideoDoc(this.defaultType);
        },
        isAudio: function(type){
            return Util.isAudioDoc(type) || Util.isAudioDoc(this.defaultType);
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
