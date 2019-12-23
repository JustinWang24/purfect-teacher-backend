if(document.getElementById('school-teacher-management-visitors-app')){
    new Vue({
        el:'#school-teacher-management-visitors-app',
        data(){
            return {
                visitors: [],
                selectedDevice: {
                    building:{},
                    room:{}
                },
                showDetailFlag: false,
            }
        },
        created(){
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            this.visitors = JSON.parse(dom.dataset.visitors);
        },
        methods: {
            showDetail: function (row) {
                this.showDetailFlag = true;
                this.selectedDevice = row;
            }
        }
    })
}