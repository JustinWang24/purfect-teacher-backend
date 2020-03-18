@extends('layouts.h5_app')
@section('content')
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
<link href="http://libs.baidu.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">

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
@endsection
