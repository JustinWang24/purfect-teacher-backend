import { Constants } from "../../common/constants";
import { Util } from "../../common/utils";

if (document.getElementById('student-list-app')) {
    new Vue({
        el: '#student-list-app',
        data() {
            return {
                isLoading: false,
                apiToken: null
            }
        },
        created() {
            const dom = document.getElementById('app-init-data-holder');
            this.apiToken = dom.dataset.apitoken;
        },
        methods: {
            // 查看详情
            viewMyApplication: function (userFlow) {
                let url = '/pipeline/flow/view-history?user_flow_id=' + userFlow.id;
                if (!Util.isEmpty(this.apiToken)) {
                    url = '/h5/flow/user/view-history?user_flow_id=' + userFlow.id + '&api_token=' + this.apiToken;
                }
                window.location.href = url;
            },
        }
    });
}