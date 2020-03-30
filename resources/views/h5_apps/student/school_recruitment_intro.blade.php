<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
<link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<style>
body{background: #e8e8e8;font-family: '微软雅黑';}
.main{margin-left:10px;margin-right:10px;}
img{max-width: 100% !important;}
</style>
    <div class="school-intro-container">
        <div class="main">
            <div class="intro-content">
                {!! $config ? $config->recruitment_intro : '还没有添加招生简章' !!}
            </div>
        </div>
    </div>
