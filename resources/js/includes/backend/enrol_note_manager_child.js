/**
 * 校园风光
 */
import {} from "../../common/welcomes";
import {Constants} from "../../common/constants";
import {Util} from "../../common/utils";
import EleUploadVideo from 'vue-ele-upload-video'
if(document.getElementById('enrol-note-manager-child')){
    new Vue({
        el:'#enrol-note-manager-child',
        data(){
            return {
			  fileList: [],
              video: '', // 视频地址
              image_url: '', // 封面图
            }
        },
        created(){
          this.getCampusVideInfo();
        },
        methods: {
          saveFile(response) {
            let fileJson = eval(response);
            this.image_url = fileJson.data.path;
            console.log(this.image_url);
            this.saveCampusVideInfo(this.image_url,3);
          },
          handlePreview(file) {
          },
          //点击上传图片
          handleRemove() {
            this.image_url = '';
          },
          // 获取
          getCampusVideInfo: function () {
            let _that_ = this;
            axios.get(Constants.API.CAMPUS_VIDEO.GET_CAMPUS_VIDEO, {}).then((res) => {
              if (Util.isAjaxResOk(res)) {
                _that_.video = !Util.isAjaxResOk(res.data.data.videoInfo.url) ? res.data.data.videoInfo.url : "";
                if(!Util.isAjaxResOk(res.data.data.imageInfo.url)){
                    let obj = {};
                    obj.name = "";
                    obj.url = res.data.data.imageInfo.url;
                    _that_.fileList = [obj];
                }
                _that_.image_url = !Util.isAjaxResOk(res.data.data.imageInfo.url) ? res.data.data.imageInfo.url : "";
				        console.log("========================111");
                console.log(res.data);
				        console.log("========================222");
              }
            });
          },
          // 保存
          saveCampusVideInfo: function (videoUrl,type) {
            if (Util.isEmpty(videoUrl)) {
              this.$message.error("请上传视频文件");
            }
            axios.post(Constants.API.CAMPUS_VIDEO.SAVE_CAMPUS_VIDEO, {"videoUrl":videoUrl,"type":type}).then((res) => {
              if (Util.isAjaxResOk(res)) {
				  return true;
              } else {
                this.$message.error(res.data.message);
              }
            });
          },
          handleUploadError (error) {
            this.$notify.error({
              title: '上传提示',
              message: '无法上传文件'
            })
            // eslint-disable-next-line no-console
            console.log('error', error)
          },
          handleResponse(response, file) {
            this.saveCampusVideInfo(response.data.path,2);
			        return window.location.protocol+"//"+window.location.host+'/'+response.data.path;
          },
        },
      components: {
        EleUploadVideo
      }
    })
}
