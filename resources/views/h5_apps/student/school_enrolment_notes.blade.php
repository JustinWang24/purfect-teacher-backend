<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
<link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<div class="school-intro-container">
<style>
body{background: #e8e8e8;font-family: '微软雅黑';}
.main{margin-left:10px;margin-right:10px;}
img{max-width: 100% !important;}
</style>
<div class="main">
	<div class="intro-content">
	{!! $note ? $note->content : '还没有添加报名须知' !!}
	</div>
	</div>
</div>