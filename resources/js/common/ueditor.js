$R('#content',{
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
	fileUpload: '/api/wysiwyg/files/upload?uuid={{ \Illuminate\Support\Facades\Auth::user()->id }}',  // 文件上传的 Action
	fileManagerJson: '/api/wysiwyg/files/view?uuid={{ \Illuminate\Support\Facades\Auth::user()->id }}', // 已存在的文件的资源 URL, 返回为 json 格式
	imageUpload: '/api/wysiwyg/images/upload?uuid={{ \Illuminate\Support\Facades\Auth::user()->id }}', // 图片上传的 Action
	imageManagerJson: '/api/wysiwyg/images/view?uuid={{ \Illuminate\Support\Facades\Auth::user()->id }}', // 已存在的图片的资源 URL, 返回为 json 格式
});