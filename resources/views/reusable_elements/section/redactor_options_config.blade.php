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
    fileUpload: '/api/wysiwyg/files/upload?uuid={{ $uuid }}',  // 文件上传的 Action
    fileManagerJson: '/api/wysiwyg/files/view?uuid={{ $uuid }}', // 已存在的文件的资源 URL, 返回为 json 格式
    imageUpload: '/api/wysiwyg/images/upload?uuid={{ $uuid }}', // 图片上传的 Action
    imageManagerJson: '/api/wysiwyg/images/view?uuid={{ $uuid }}', // 已存在的图片的资源 URL, 返回为 json 格式
}