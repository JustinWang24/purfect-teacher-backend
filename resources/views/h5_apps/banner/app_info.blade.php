<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>易同学</title>
    @if($app == 1)
        <link rel="stylesheet" type="text/css" href="/special/share/css/global1.css" />
    @endif
    @if($app == 2)
        <link rel="stylesheet" type="text/css" href="/special/share/css/global2.css" />
    @endif
</head>
<body>
<div class="wrap">
    <div id="weixin-tip2">
        <div><img src="/special/share/img/ts.png" /></div>
        <span id="close2" title="关闭 ">×</span>
    </div>
</div>

<script type="text/javascript ">

    var is_weixin = (function() {
        var ua = navigator.userAgent.toLowerCase();
        if (ua.match(/MicroMessenger/i) == "micromessenger"||ua.match(/WeiBo/i) == "weibo") {
            return true;
        }else if(ua.indexOf('mobile mqqbrowser')>-1){
            return true;
        }else if(ua.indexOf('Safari')>-1){
            return true;
        }else{
            return false;
        }
    })();

    window.onload = function() {
        var pla = ismobile(1);
        var winHeight = typeof window.innerHeight != 'undefined' ? window.innerHeight : document.documentElement.clientHeight; //兼容IOS
        var tip2 = document.getElementById("weixin-tip2");
        var close2 = document.getElementById("close2");

        if(is_weixin) {
            if(pla == 0) {
                window.location.href = "{{  $data->version_downurl }}";
            } else {
                tip2.style.display = 'block';
                tip2.style.height = winHeight + 'px'; //兼容IOS弹窗整屏
                close2.onclick = function() {
                    tip2.style.display = 'none';
                }
            } 
        }else{
            pla == 0 ? window.location.href = "{{ route('api.version.download',['sid'=>$data->sid]) }}":window.location.href = "{{  $data->version_downurl }}";
        }

        /**
         * [isMobile 判断平台]
         * @param test: 0:iPhone    1:Android
         */
        function ismobile(test) {
            var u = navigator.userAgent,
                app = navigator.appVersion;
            if(/AppleWebKit.*Mobile/i.test(navigator.userAgent) || (/MIDP|SymbianOS|NOKIA|SAMSUNG|LG|NEC|TCL|Alcatel|BIRD|DBTEL|Dopod|PHILIPS|HAIER|LENOVO|MOT-|Nokia|SonyEricsson|SIE-|Amoi|ZTE/.test(navigator.userAgent))) {
                if(window.location.href.indexOf("?mobile ") < 0) {
                    try {
                        if(/iPhone|mac|iPod|iPad/i.test(navigator.userAgent)) {
                            return '0';
                        } else {
                            return '1';
                        }
                    } catch(e) {}
                }
            } else if(u.indexOf('iPad') > -1) {
                return '0';
            } else {
                return '1';
            }
        };
    }
</script>

</body>
</html>
