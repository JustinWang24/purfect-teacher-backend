/**
 * 文件管理器
 */
if (document.getElementById('file-manager-app')){
    new Vue({
        el: '#file-manager-app',
        data(){
            return {
                showFileManagerFlag: false,
            }
        },
        created() {

        },
        methods: {
            showFileManager: function(){
                this.showFileManagerFlag = true;
            },
            handleClose: function(){
                this.showFileManagerFlag = false;
            }
        }
    });
}