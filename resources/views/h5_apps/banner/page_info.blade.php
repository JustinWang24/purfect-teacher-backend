<!DOCTYPE html>
<html lang='en'>
<title></title>
<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <link href="http://libs.baidu.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
    <script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
    <script src="http://libs.baidu.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
	<title></title>
	<style>
	img {
		max-width: 100% !important;
	}
</style>
</head>
<body style="margin:10px;font-family: '微软雅黑';font-size: 85%;">
{{isset($data->content)?$data->content:''}}
</body>
<script>
	$(window).load(function(){
		$("img").addClass("img-responsive");
	})
</script>
</html>
