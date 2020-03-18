/**
 * 校园风光
 */
import {} from "../../common/welcomes";
import {Constants} from "../../common/constants";
import {Util} from "../../common/utils";
import EleUploadVideo from 'vue-ele-upload-video'
if(document.getElementById('campus-intro-app')){
    new Vue({
        el:'#campus-intro-app',
        data(){
            return {
              video: '',
            }
        },
        created(){
          this.getCampusVideInfo();
        },
        methods: {
          // 保存视频
          getCampusVideInfo: function () {
            let _that_ = this;
            axios.get(Constants.API.CAMPUS_VIDEO.GET_CAMPUS_VIDEO, {}).then((res) => {
              if (Util.isAjaxResOk(res)) {
                _that_.video = res.data.videoInfo.url;
                console.log(res.data);
              }
            });
          },
          // 保存视频
          saveCampusVideInfo: function (videoUrl) {
            if (Util.isEmpty(videoUrl)) {
              this.$message.error("请上传视频文件");
            }
            axios.post(Constants.API.CAMPUS_VIDEO.SAVE_CAMPUS_VIDEO, {"videoUrl":videoUrl}).then((res) => {
              if (Util.isAjaxResOk(res)) {
                console.log(window.location.protocol+"//"+window.location.host+'/'+videoUrl);
                return Util.buildUrl(videoUrl);
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
            this.saveCampusVideInfo(response.data.path);

          },
        },
      components: {
        EleUploadVideo
      }
    })
}
