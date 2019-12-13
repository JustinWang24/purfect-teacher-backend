// 快速定位用户的搜索框: 会更加当前的状况, 来搜索用户和学院 系等
import {Util} from "../../common/utils";

if(document.getElementById('user-quick-search-app')){
    new Vue({
        el:'#user-quick-search-app',
        methods: {
            onItemSelected: function(payload){
                // 如果指定了 nextAction, 就跳转到其指定的页面, 否则按照 id 为用户的 id 的规则, 打开用户的 profile
                if(Util.isEmpty(payload.item.nextAction)){
                    // 按照 id 为用户的 id 的规则, 打开用户的 profile
                    const nextAction = '/verified_student/profile/edit?uuid=' + payload.item.uuid;
                    window.open(nextAction, '_blank');
                }else {
                    window.open(payload.item.nextAction, '_blank');
                }
            }
        }
    })
}