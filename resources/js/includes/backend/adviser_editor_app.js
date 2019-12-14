import {Util} from "../../common/utils";
import {Constants} from "../../common/constants";

if(document.getElementById('adviser-editor-app')){
    new Vue({
        el: '#adviser-editor-app',
        data(){
            return {
                adviser:{

                },
                postUrl: '',
                redirect: '',
                type: -1,
            }
        },
        created(){
            const dom = document.getElementById('app-init-data-holder');
            this.adviser = JSON.parse(dom.dataset.adviser);
            this.postUrl = dom.dataset.url;
            this.redirect = dom.dataset.redirect;
            this.type = parseInt(dom.dataset.type);
        },
        methods: {
            onSubmit: function(){
                // 根据类型组合数据
                switch (this.type){
                    case Constants.ADVISER.DEPARTMENT:
                        break;
                    default:
                        break;
                }

                if(this.type > 0 && !Util.isEmpty(this.postUrl)){
                    axios.post(
                        this.postUrl,
                        {adviser: this.adviser}
                    ).then(res => {
                        if(Util.isAjaxResOk(res)){
                            this.$message({
                                type:'success',
                                message:'保存成功! 请等待, 页面跳转中 ...'
                            });
                            window.location.href = this.redirect;
                        }
                        else {
                            this.$message.error(res.data.message);
                        }
                    });
                }
            },
            adviserUpdated: function(payload){
                switch (this.type){
                    case Constants.ADVISER.DEPARTMENT:
                        this.adviser.user_id = payload.item.id;
                        this.adviser.user_name = payload.item.value;
                        break;
                    case Constants.ADVISER.GRADE:
                        this.adviser.adviser_id = payload.item.id;
                        this.adviser.adviser_name = payload.item.value;
                        break;
                    case Constants.ADVISER.STUDENTS:
                        this.adviser.monitor_id = payload.item.id;
                        this.adviser.monitor_name = payload.item.value;
                        break;
                    default:
                        break;
                }

            }
        }
    });
}