// 学校的课程管理
import {Constants} from "../../../common/constants";
import {Util} from "../../../common/utils";

$(document).ready(function(){
    if(document.getElementById('course-materials-manager-app')){
        new Vue({
            el: '#course-materials-manager-app',
            data(){
                return {
                    course:null,
                    teacher:null,
                    notes:null,
                    configOptions:{}
                }
            },
            created: function(){
                const dom = document.getElementById('app-init-data-holder');
                this.course = JSON.parse(dom.dataset.course);
                this.teacher = JSON.parse(dom.dataset.teacher);
                this.notes = JSON.parse(dom.dataset.notes);
                this.configOptions = Util.getWysiwygGlobalOption(this.teacher.uuid);
            }
        });
    }
});