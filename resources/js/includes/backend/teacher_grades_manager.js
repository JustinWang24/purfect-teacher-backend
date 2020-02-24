import {Util} from "../../common/utils";
import {Constants} from "../../common/constants";

if (document.getElementById('teacher-assistant-grades-manager-app')) {
    new Vue({
        el: '#teacher-assistant-grades-manager-app',
        data(){
            return {
                schoolId: null,
                filterOptions: [],
                filterValue: '',
            }
        },
        created(){
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            // 载入时间选择
            console.log('班级管理');
        },
        methods: {
            getTimeScreen: function () {
                const url = Util.buildUrl(Constants.API.TEACHER_WEB.TIME_SCREEN);
                axios.get(url).then((res) => {
                    if (Util.isAjaxResOk(res)) {
                        let data = res.data.data;
                        this.filterOptions = [];
                        data.forEach((item, index) => {
                            let options = {};
                            options.value = JSON.stringify(item);
                            options.label = item.name;
                            this.filterOptions.push(options)
                        });
                    }
                }).catch((err) => {

                });
            },
        }
    });
}
