<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>小易助手</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <!--开启对web app的支持-->
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <!--主要是正对苹果手机将数字自动识别为号码-->
    <meta name="format-detection" content="telephone=no"/>
    <!-- 忽略识别邮箱，主要是针对安卓手机会自动将符合邮箱格式的字符串识别为邮箱地址-->
    <meta content="email=no" name="format-detection"/>
    <meta name="apple-itunes-app" content="app-id=myAppStoreID, affiliate-data=myAffiliateData, app-argument=myURL"/>
    <!-- 添加智能 App 广告条 Smart App Banner：告诉浏览器这个网站对应的app，并在页面上显示下载banner:https://developer.apple.com/library/ios/documentation/AppleApplications/Reference/SafariWebContent/PromotingAppswithAppBanners/PromotingAppswithAppBanners.html -->
    <!-- 针对手持设备优化，主要是针对一些老的不识别viewport的浏览器，比如黑莓 -->
    <meta name="HandheldFriendly" content="true">
    <!-- 微软的老式浏览器 -->
    <meta name="MobileOptimized" content="320">
    <!-- uc强制竖屏 -->
    <meta name="screen-orientation" content="portrait">
    <!-- QQ强制竖屏 -->
    <meta name="x5-orientation" content="portrait">
    <!-- UC强制全屏 -->
    <meta name="full-screen" content="yes">
    <!-- QQ强制全屏 -->
    <meta name="x5-fullscreen" content="true">
    <!-- UC应用模式 -->
    <meta name="browsermode" content="application">
    <!-- QQ应用模式 -->
    <meta name="x5-page-mode" content="app">
    <!-- windows phone 点击无高光 -->
    <meta name="msapplication-tap-highlight" content="no">
    <link rel="stylesheet" href="__PUBLIC__/api/css/wifinotice/mescroll.min.css">
    <link rel="stylesheet" href="__PUBLIC__/api/css/wifinotice/common.css">
    <link rel="stylesheet" href="__PUBLIC__/api/css/wifinotice/quesList.css">
</head>

<body>
<!--标题-->
<!--div class="header"><a class="btn-left back"></a>小易助手</div-->
<!--滑动区域-->
<div id="mescroll" class="mescroll">
    <ul id="helplist" class="helplist">
        @foreach($dataList as $key=>$val)
            <li>
                <a href="{{ route('api.wifi.page_view',['noticeid'=>$val->noticeid]) }}" style="color:black;display:block;">
                    {{$val->notice_title}}
                </a>
            </li>
        @endforeach
    </ul>
</div>
<script src="__PUBLIC__/api/js/wifinotice/mescroll.min.js" type="text/javascript" charset="utf-8"></script>
<script src="__PUBLIC__/api/js/wifinotice/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script src="__PUBLIC__/api/js/wifinotice/common.js" type="text/javascript" charset="utf-8"></script>
</body>
</html>