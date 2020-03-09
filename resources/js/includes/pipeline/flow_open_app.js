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
                provinceList: provinceList
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
        },
        methods: {
            closeWindow: function(){
                window.opener = null;
                window.open('','_self');
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
                        console.log(data)
                        this.formList = data.options;
                        // this.formList.forEach(function(item, index) {
                        //     item.value = '';
                        // });
                        console.log(this.formList)
                    }
                }).catch((err) => {

                });
            },
            onSubmit() {
                console.log(1)
                console.log(this.formList)
                let options = [];
                let url = '/api/pipeline/flow/start'
                this.formList.forEach(function (item, index) {
                    let option = {};
                    option.id = item.id;
                    option.value = item.value;
                    options.push(option)
                });
                let params = {
                    action: {
                        flow_id: this.action.flow_id,
                        content:"",
                        attachments:[],//附件列表[mediaid1,mediaid2]
                        urgent: true,//是否加急
                    },
                    options: options,
                    is_app: true
                };
                axios.post(url, params).then((res) => {
                    if (Util.isAjaxResOk(res)) {
                        this.$message({
                            message: '发起成功',
                            type: 'success'
                        });
                        setTimeout(() => {
                            this.closeWindow()
                        }, 400)
                    } else {
                        this.$message({
                            message: '发起失败',
                            type: 'warning'
                        });
                    }
                }).catch((err) => {
                    this.$message({
                        message: '发起失败',
                        type: 'warning'
                    });
                });
            },
            onConfirm(item) {
                item.value = this.setTime(item.time, item.extra.dateType);
                item.extra.showPicker = false;
            },
            onConfirmRange(item) {
                item.valueS = this.setTime(item.timeS, item.extra.dateType);
                item.extra.showPickerS = false;
            },
            onConfirmE(item) {
                item.valueE = this.setTime(item.timeE, item.extra.dateType);
                item.extra.showPickerE = false;
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
            pickFileHandler: function (payload) {
                this.showFileManagerFlag = false;
                const attachment = {
                    id: null,
                    action_id: null,
                    media_id: payload.file.id,
                    url: payload.file.url,
                    file_name: payload.file.file_name
                };
                this.action.attachments.push(attachment);
            },
            uploadImg(img) {
                console.log(img)

            }
        }
    });
}
