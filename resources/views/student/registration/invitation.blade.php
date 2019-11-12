<?php
/**
 * @var \App\Models\RecruitStudent\RegistrationInformatics $form
 */
?>
<html>
<head>
    <title>录取通知书</title>
</head>
<body>
    <div style="width: 600px;margin: 0 auto;display: block;">
        <h1 style="font-size: 36px;line-height: 40px;text-align: center;margin-bottom: 30px;">
            录取通知书
        </h1>
        <p style="line-height: 40px;font-size: 24px;">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="text-decoration: underline">{{ $form->name }}</span> 同学, 你被录取到我校 <span style="text-decoration: underline">{{ $form->major->name }}</span> 专业学习.
            请于 <span style="text-decoration: underline">{{ Carbon\Carbon::today()->format('Y 年 m 月 d 日') }}</span> 持本通知书来我校报道.
        </p>
        <p style="line-height: 40px;font-size: 24px;">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;请妥善保存本通知书, 并在报到时向负责迎新的老师出示下方二维码, 办理入学手续.
        </p>
        <p style="text-align: center;">
            <img src="{{ $form->profile->getQrCode() }}" alt="">
        </p>
    </div>
</body>
</html>