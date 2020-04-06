/**
 * Banner 管理
 */
import { Constants } from "../../common/constants";
import { Util } from "../../common/utils";
$(document).ready(function(){
  if(document.getElementById('banner-manager-app')){
      new Vue({
          el:'#banner-manager-app',
          data(){
              return {
                school_id: 0, // 学校id
                isshow1: false, // url id
                isshow1Lable: "名称", // 名称
                isshow1Placeholder: "请填写值", // 文本框名称
                configOptions:{
                  lang:'zh_cn',
                  plugins: [
                      'fontsize',
                      'fontcolor',
                      'alignment',
                      'fontfamily',
                      'table',
                      'specialchars',
                  ],
                },
                isshow2: false, // 内容
                minHeight: "300px", // 内容
                fileList: [], // 文件列表
                bannerFormFlag: false, // 表单
                typeList: [], // 类型选择
                bannerFormInfo: {
                  id:0, // id
                  app : 0, // 终端
                  posit : 0, // 位置
                  type : 0, // 类型
                  title : "", // 标题
                  external : "", // url id
                  content : "", // 内容
                  image_url:'', // 图片
                  public : true, // 浏览权限
                  status: true, // 发布状态
                },
              }
          },
          computed: {
          },
          created(){
            const dom = document.getElementById('app-init-data-holder');
            this.school_id = dom.dataset.school;
          },
          methods: {
            saveFile(response) {
              console.log('saveFile',response)
              let fileJson = eval(response);
              this.bannerFormInfo.image_url = fileJson.data.path;
            },
            handlePreview(file) {
            },
            //点击上传图片
            handleRemove() {
              this.bannerFormInfo.image_url = '';
            },
            // 获取分类
            getTypeList() {
              axios.post(Constants.API.BANNER.POST_TYPE, {}).then((res) => {
                if (Util.isAjaxResOk(res)) {
                  this.typeList = res.data.data;
                }
              }).catch((err) => {
                console.log(err)
              });
            },
            // 编辑数据
            editBanner(id) {
                let _that_ = this;
                _that_.bannerFormFlag = true;
                _that_.getTypeList();

                axios.post(Constants.API.BANNER.GET_BANNER_ONE, {id:id}).then((res) => {
                  if (Util.isAjaxResOk(res)) {
                    console.log('1232132',res)
                    // 图片回显
                    let obj = {};
                    obj.name = "";
                    obj.url = res.data.data.image_url;
                    _that_.fileList = [obj];
                    // 数据回显
                    _that_.bannerFormInfo.id = res.data.data.id;
                    _that_.bannerFormInfo.app = res.data.data.app;
                    _that_.bannerFormInfo.posit = res.data.data.posit;
                    _that_.bannerFormInfo.type = res.data.data.type;
                    _that_.bannerFormInfo.title = res.data.data.title;
                    _that_.bannerFormInfo.content = res.data.data.content;
                    _that_.bannerFormInfo.external = res.data.data.external;
                    _that_.bannerFormInfo.image_url = res.data.data.image_url;
                    _that_.bannerFormInfo.public = res.data.data.public == 1?true:false;
                    _that_.bannerFormInfo.status = res.data.data.status == 1?true:false;
                    console.log("===================修改===================");
                    console.log(_that_.bannerFormInfo);
                    _that_.handlechange({0:res.data.data.app,1:res.data.data.posit,2:res.data.data.type});
                  }
                }).catch((err) => {
                  console.log(err)
                });
              },
            // 分类改变值
            handlechange(value,type=1) {
              this.isshow1=false;
              this.isshow2=false;
              console.log("--------------------分类变更值---------------------");
              console.log(value);
              this.bannerFormInfo.app = !Util.isEmpty(value[0]) ? value[0] : 0;
              this.bannerFormInfo.posit = !Util.isEmpty(value[1]) ? value[1] : 0;
              let bannerFormInfoType = !Util.isEmpty(value[2]) ? value[2] : 0;
              if ( bannerFormInfoType > 0) {
                if (bannerFormInfoType == 1) { // 1 => '无跳转'
                  this.isshow1=false;
                  this.isshow2=false;
                }
                if (bannerFormInfoType == 2) { // 2 => '图文'
                  this.isshow1=false;
                  this.isshow2=true;
                  console.log("--------图文----")
                  console.log(this.isshow2);
                }
                if (bannerFormInfoType == 3) { // 3 => 'URL'
                  this.isshow1=true;
                  this.isshow1Lable="URL";
                  this.isshow1Placeholder="URL格式为：http://app.pftytx.com/";
                  this.isshow2=false;
                }
                if (bannerFormInfoType == 4) { // 4 => '校园网'
                  this.isshow1=false;
                  this.isshow2=false;
                }
                if (bannerFormInfoType== 5) { // 5 => '招生主页'
                  this.isshow1=false;
                  this.isshow2=false;
                }
                if (bannerFormInfoType == 6) { // 6 => '迎新主页'
                  this.isshow1=false;
                  this.isshow2=false;
                }
                if (bannerFormInfoType == 7) { // 7 => '选课列表页'
                  this.isshow1=false;
                  this.isshow2=false;
                }
                if (bannerFormInfoType== 8) { // 8 => '社区动态详情'
                  this.isshow1=true;
                  this.isshow1Lable="动态";
                  this.isshow1Placeholder="例如：800";
                }
                if (bannerFormInfoType == 11) { // 1 => '无跳转'
                  this.isshow1=false;
                  this.isshow2=false;
                }
                if (bannerFormInfoType == 12) { // 2 => '图文'
                  this.isshow1=false;
                  this.isshow2=true;
                }
                if (bannerFormInfoType == 13) { // 3 => 'URL'
                  this.isshow1=true;
                  this.isshow1Lable="URL";
                  this.isshow1Placeholder="URL格式为：http://app.pftytx.com/";
                  this.isshow2=false;
                }
              }
            },
            // 新增轮播图
            createNewBanner() {
              this.bannerFormFlag = true;
              this.getTypeList();
              this.isshow1=false;
              this.isshow2=false;
              this.bannerFormInfo.app = 0;
              this.bannerFormInfo.posit = 0;
              this.bannerFormInfo.type = 0;
              this.bannerFormInfo.title = "";
              this.bannerFormInfo.content = "";
              this.bannerFormInfo.external = "";
              this.bannerFormInfo.image_url = "";
              this.bannerFormInfo.public = 1;
              this.bannerFormInfo.status = 1;
            },
          // 保存
          saveBannerInfo: function () {
            let _that_ =  this;
            this.bannerFormInfo.school_id = this.school_id;
            axios.post(Constants.API.BANNER.POST_SAVE_BANNER, this.bannerFormInfo).then((res) => {
              if (Util.isAjaxResOk(res)) {
                this.$message({
                  type:'success',
                  message:res.data.message
                });
                _that_.bannerFormFlag = false;
                window.location.reload();
              } else {
                this.$message.error(res.data.message);
              }
            }).catch((err) => {
              this.$message.error(res.data.message);
            });
          },
          deleteBanner: function(id){
              this.$confirm('您确认要删除吗?', '提示', {
                  confirmButtonText: '确定',
                  cancelButtonText: '取消',
                  type: 'warning'
              }).then(() => {
                  window.location.href = '/school_manager/banner/delete?id=' + id;
              }).catch(() => {
                  this.$message({
                      type: 'info',
                      message: '已取消删除'
                  });
              });
          }
          }
      })
  }
})
