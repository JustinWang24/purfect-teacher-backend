<template>
  <div>
    <el-card shadow="always" v-if="fileDic" class="current-file-box">
        <file-type :file-dic="fileDic" :default-type="fileDic.type"></file-type>
        <div v-if="hasDeleteButton">
            <el-button
                    class="btn-delete"
                    icon="el-icon-circle-close"
                    type="text"
                    v-on:click="deleteHandler"
            ></el-button>
        </div>
    </el-card>
  </div>
</template>

<script>
import FileType from './FileType';
import {Constants} from "../../../common/constants";

export default {
    name: 'FilePreview',
    components: {
      FileType,
    },
    props: {
        fileDic: Object, hasDeleteButton: Boolean,
    },
    data(){
        return {
            defaultFileType: Constants.FILE_TYPE.GENERAL
        }
    },
    methods: {
        deleteHandler: function(){
            this.$emit('preview-delete',{file: this.fileDic});
        }
    }
}
</script>

<style lang="scss" scoped>
  .current-file-box{
      position: relative;
      p{
        margin-bottom: 5px;
        font-size: 13px;
      }
      .btn-delete{
          position: absolute;
          top: -11px;
          right: -11px;
          width: 40px;
          height: 40px;
          color: red;
          padding: 0;
      }
  }
</style>
