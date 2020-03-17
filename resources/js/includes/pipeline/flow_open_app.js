/**
 * Open一个流程
 */
import {Util} from "../../common/utils";
import {start} from "../../common/flow";
import provinceList from './province_list';

if (document.getElementById('pipeline-flow-open-app')) {
  new Vue({
    el: '#pipeline-flow-open-app',
    data(){
      return {
        schoolId: null,
        apiToken: null,
        action: {
          id: null,
          flow_id: '',
          node_id: '',
          content: '',
          attachments: [],
          urgent: false,
          options: []
        },
        formList: [],
        showFileManagerFlag: false,
        minDate: new Date(1900, 0, 1),
        maxDate: new Date(2100, 1, 1),
        provinceList: provinceList,
        canSubmit: true,
        part: [],
        selectParts: []
      }
    },
    created(){
      const dom = document.getElementById('app-init-data-holder');
      this.schoolId = dom.dataset.school;
      this.apiToken = dom.dataset.apitoken;
      // this.action.node_id = dom.dataset.nodeid;
      // this.appRequest = !Util.isEmpty(dom.dataset.apprequest);
      this.action.flow_id = dom.dataset.flowid;

      this.action.options = JSON.parse(dom.dataset.nodeoptions);
      this.getFormList();
      // this.part[1] = {level: 2}
      this.getPart();
    },
    methods: {
      closeWindow: function () {
        window.opener = null;
        window.open('', '_self');
        window.close();
      },
      getFormList () {
        const url = '/api/pipeline/flow/open';
        let params = {};
        params.api_token = this.apiToken;
        params.flow_id = this.action.flow_id;
        axios.post(url, params).then((res) => {
          if (Util.isAjaxResOk(res)) {
            let data = res.data.data;
            // console.log(data)
            this.formList = data.options;
            this.formList.forEach((item, index) => {
              if (item.type == 'input' || item.type == 'textarea' || item.type == 'number') {
                this.$set(item, 'value', '')
              }
            });
            // console.log(this.formList)
          }
        }).catch((err) => {
          this.$message({
            message: '获取表单信息失败',
            type: 'warning'
          });
        });
      },
      onSubmit() {
        // console.log(1)
        // console.log(this.formList)
        let options = [];
        let url = '/api/pipeline/flow/start'
        this.formList.forEach(function (item, index) {
          let option = {};
          option.id = item.id;
          option.value = item.value ? item.value : '';
          options.push(option)
        });
        let params = {
          action: {
            flow_id: this.action.flow_id,
            content: "",
            attachments: [],//附件列表[mediaid1,mediaid2]
            urgent: false,//是否加急
          },
          options: options,
          is_app: true
        };
        if (this.canSubmit) {
          this.canSubmit = false;
          axios.post(url, params).then((res) => {
            if (Util.isAjaxResOk(res)) {
              this.$message({
                message: '提交成功',
                type: 'success'
              });
              // console.log(res)
              setTimeout(() => {
                window.location.href = res.data.data.url;
              }, 400)
            } else {
              this.canSubmit = true;
              this.$message({
                message: '提交失败',
                type: 'warning'
              });
            }
          }).catch((err) => {
            this.canSubmit = true;
            this.$message({
              message: '提交失败',
              type: 'warning'
            });
          });
        }

      },
      onConfirm(item) {
        item.value = this.setTime(item.time, item.extra.dateType);
        item.extra.showPicker = false;
      },
      onConfirmS(item) {
        item.valueS = this.setTime(item.timeS, item.extra.dateType);

        if (item.timeS > item.timeE) {
          item.timeE = item.timeS
          item.valueE = item.valueS
        }

        item.value = item.valueS + (item.valueE ? ' ~ ' + item.valueE : '');
        console.log(item.valueE ? item.valueE : '')
        console.log(item, item.value)
        item.extra.showPickerS = false;
      },
      onConfirmE(item) {
        item.valueE = this.setTime(item.timeE, item.extra.dateType);

        if (item.timeE < item.timeS) {
          item.timeS = item.timeE
          item.valueS = item.valueE
        }

        item.value = (item.valueS ? item.valueS + ' ~ ' : '') + item.valueE;
        console.log(item, item.value)
        item.extra.showPickerE = false;
      },
      showPicker(item) {
        item.extra.showPicker = true;
        if (!item.value) {
          item.time = new Date();
        }
      },
      showPickerStart(item) {
        item.extra.showPickerS = true;
        if (!item.timeS) {
          item.timeS = new Date();
        }
      },
      showPickerEnd(item) {
        item.extra.showPickerE = true;
        if (!item.timeE) {
          item.timeE = new Date();
        }
      },
      setTime(time, type) {
        let year = time.getFullYear();
        let mon = time.getMonth() + 1;
        let day = time.getDate();
        let newTime;

        if (mon < 10) mon = '0' + mon;
        if (day < 10) day = '0' + day;
        newTime = year + '-' + mon + '-' + day;

        if (type == 1) {
          let h = time.getHours();
          let m = time.getMinutes();
          if (h < 10) h = '0' + h;
          if (m < 10) m = '0' + m;
          newTime = year + '-' + mon + '-' + day + '  ' + h + ':' + m;
        }
        return newTime
      },
      setArea(val, item) {
        item.value = val[0].name + '/' + val[1].name + '/' + val[2].name;
        item.extra.showPicker = false;
      },
      uploadImg(img) {
        // console.log(img)

      },
      showDepart(item) {
        // if (this.part.length == 0 || item.value) {
        this.getPart()
        // }
        item.extra.showPicker = true;
        item.cancelText = '下一步';
        item.partEnter = false;
      },
      partNext(item) {
        if (this.selectParts.length == 0) {
          return
        }
        this.$set(item, 'cancelText', '确定');
        if (item.partEnter) {
          this.$set(item.extra, 'showPicker', false)
          // item.extra.showPicker = false;
          item.cancelText = '下一步';
          item.value = '';
          this.selectParts.forEach(function (item2, index) {
            item.value += item2.name + '/';
          });
          this.selectParts = [];
        }
        this.$set(item, 'partEnter', true);
        this.$forceUpdate();
      },
      clickPart(item, parentItem, type) {
        item.active = true;
        this.getPart(item, parentItem, type);
      },
      getPart(item, parentItem, type) {
        let url = '/Oa/tissue/getOrganization',
          params = {}, level = 1;
        params.parent_id = 0;
        params.type = 1;
        console.log(item)
        if (item) {
          level = item.level;
          params.parent_id = item.id;
        }
        axios.post(url, params).then((res) => {
          if (Util.isAjaxResOk(res)) {
            // console.log(res)
            let data = res.data.data;
            data.active = [];
            if (params.parent_id == 0) {
              this.part = [data]
              return
            }
            this.part.splice(level, this.part.length + level, data);
            if (data.organ.length != 0) {
              this.selectParts = [];
              parentItem.organ.forEach((itemPart, index) => {
                itemPart.active = false;
              });
              item.active = true;
            } else if (data.organ.length == 0) {
              if (type == 1) {
                parentItem.organ.forEach((itemPart, index) => {
                  itemPart.active = false;
                });
                item.active = true;
                this.selectParts[0] = item;
              } else if (type == 2) {
                let noPart = true;
                this.selectParts.forEach((part, index) => {
                  if (part.id == item.id) {
                    noPart = false;
                    this.selectParts.splice(index, 1)
                  }
                });
                if (noPart) {
                  item.active = true;
                  parentItem.active.push(item.id)
                  this.selectParts.push(item)
                }
              }
            }

            this.$forceUpdate();
          }
        }).catch((err) => {
        });
      },
      pickFileHandler: function(payload){
        this.showFileManagerFlag = false;
        const attachment = {
          id:null,
          action_id: null,
          media_id: payload.file.id,
          url: payload.file.url,
          file_name: payload.file.file_name
        };
        this.action.attachments.push(attachment);
      },
      dropAttachment: function(idx, attachment){
        this.action.attachments.splice(idx, 1);
        this.$message({type:'info', message: '移除文件: ' + attachment.file_name});
      }
    }
  });
}
