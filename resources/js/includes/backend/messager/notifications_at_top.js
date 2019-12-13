/**
 * 顶部的消息通知应用
 */
import { loadMessages } from '../../../common/notifications';
import {Util} from "../../../common/utils";
if(document.getElementById('header_notification_bar')){
    new Vue({
        el:'#header_notification_bar',
        data(){
            return {
                schoolId: null,
                messages:[],
                total: 0,
                pageSize: 0,
                hasNew: false,
            };
        },
        created(){
            const dom = document.getElementById('notification-app-data-top');
            this.schoolId = dom.dataset.schoolid;
            const that = this;
            that.loadLastSixSystemMessage();
            // 每分钟查询一次
            window.setInterval(function(){
                that.loadLastSixSystemMessage();
            },60000)
        },
        methods:{
            loadLastSixSystemMessage: function(){
                if(!Util.isEmpty(this.schoolId)){
                    loadMessages(this.schoolId).then(res => {
                        if(Util.isAjaxResOk(res)){
                            // 检查是不是有新的消息
                            const newMessages = res.data.data.messages.data;
                            if(this.messages.length > 0){
                                if(newMessages[0].id !== this.messages[0].id){
                                    this.$notify.info({
                                        title: '消息',
                                        message: '你有一条新消息',
                                        duration: 0
                                    });
                                    this.hasNew = true;
                                }
                            }
                            this.messages = newMessages;
                            this.total = res.data.data.messages.to;
                            this.pageSize = res.data.data.messages.per_page;
                        }
                    })
                }
            }
        }
    })
}