if(document.getElementById('school-teacher-management-devices-app')){
    new Vue({
        el:'#school-teacher-management-devices-app',
        data(){
            return {
                devices: [],
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
            this.devices = JSON.parse(dom.dataset.devices);
        },
        methods: {
            showDetail: function (row) {
                this.showDetailFlag = true;
                this.selectedDevice = row;
            }
        }
    })
}