/**
 * 通讯录
 */
import {loadGradeContacts, loadGrades, loadOrgContacts} from "../../common/contacts";
import {Util} from "../../common/utils";

if(document.getElementById('school-contacts-list-app')){
    new Vue({
        el:'#school-contacts-list-app',
        data(){
            return {
                contacts: [],
                mates: [],
                teachers: [],
                schoolUuid: null,
                schoolId: null,
                grades:[],
                gradeId: null,
                loading: false,
            }
        },
        watch: {
            'gradeId': function(val){
                this.loading = true;
                this.mates = [];
                this.teachers = [];
                loadGradeContacts(this.schoolUuid, this.gradeId).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.mates = res.data.data.schoolmate_list;
                        this.teachers = res.data.data.teacher_list;
                    }
                    else{
                        this.$message.error('没有找到班级的数据');
                    }
                    this.loading = false;
                })
            }
        },
        created(){
            const dom = document.getElementById('app-init-data-holder');
            this.schoolUuid = dom.dataset.school;
            this.schoolId = dom.dataset.id;
            this.loadContacts();
            this.loadAllGrades();
        },
        methods: {
            loadContacts: function(){
                loadOrgContacts(this.schoolUuid).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.contacts = res.data.data.department_list;
                    }
                });
            },
            loadAllGrades: function(){
                loadGrades(this.schoolId).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.grades = res.data.data.grades;
                    }
                })
            }
        }
    });
}