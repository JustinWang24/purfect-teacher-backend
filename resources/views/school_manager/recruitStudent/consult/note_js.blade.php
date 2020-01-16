<script language="JavaScript">
    $(document).ready(function(){
        new window.Vue({
            el: '#enrol-note-manager-app',
            data() {
                return {
                    content: '{!! $note->content ?? null !!}',
                    configOptions: {
                        lang:'zh_cn',
                        plugins: [
                            'fontsize',
                            'fontcolor',
                            'alignment',
                            'fontfamily',
                            'table',
                            'specialchars',
                            'imagemanager',
                            'filemanager',
                        ],
                        fileUpload: '/your-upload-script/',
                        fileManagerJson: '/your-folder/files.json',
                        imageUpload: '/your-upload-script/',
                        imageManagerJson: '/your-folder/images.json'
                    }
                }
            }
        });
    });
</script>