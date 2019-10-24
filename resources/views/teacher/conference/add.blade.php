<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2019/10/23
 * Time: 下午4:58
 */
?>
@extends('layouts.app')

<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href="{{ asset('layui/css/layui.css') }}" rel="stylesheet" type="text/css" />
<style>
    .layui-form-label{
        width: 110px;
    }
    .block{
        width: 35%;
    }
</style>
@section('content')
    <form class="layui-form" action="">
        <div class="layui-form-item">
            <label class="layui-form-label">会议主题</label>
            <div class="layui-input-block">
                <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入会议标题" class="layui-input block">
            </div>
        </div>


        <div class="layui-inline">
            <label class="layui-form-label">会议地点</label>
            <div class="layui-input-inline">
                <select name="room_id" lay-verify="required" lay-search="">
                    <option value="">直接选择或搜索选择</option>
                    @foreach($room as $key => $val)
                    <option value="{{$val['id']}}">{{$val['name']}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">会议时间</label>
                <div class="layui-input-inline">
                    <input type="text" name="time" id="time" lay-verify="date" placeholder="yyyy-MM-dd" autocomplete="off" class="layui-input">
                </div>
            </div>
        </div>





        <div class="layui-inline">
            <label class="layui-form-label">会议负责人</label>
            <div class="layui-input-inline">
                <select name="modules" lay-verify="required" lay-search="">
                    <option value="">直接选择或搜索选择</option>
                    @foreach($teacher as $key => $val)
                    <option value="{{$val['id']}}">{{$val['name']}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="layui-form-item">


            <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
                <legend>参会人</legend>
            </fieldset>
            {{--<div class="layui-btn-container">
                <button type="button" class="layui-btn" lay-demotransferactive="getData">获取右侧数据</button>
                <button type="button" class="layui-btn" lay-demotransferactive="reload">重载实例</button>
            </div>--}}

            <div id="test7" class="demo-transfer"></div>
        </div>




        <div class="layui-form-item">
            <label class="layui-form-label">会议结束签退</label>
            <div class="layui-input-block">
                <input type="radio" name="sex" value="男" title="男" checked="">
                <input type="radio" name="sex" value="女" title="女">
                <input type="radio" name="sex" value="禁" title="禁用" disabled="">
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">开始签到时间</label>
                <div class="layui-input-inline">
                    <input type="text" name="date" id="date" lay-verify="date" placeholder="yyyy-MM-dd" autocomplete="off" class="layui-input">
                </div>
            </div>

            <div class="layui-inline">
                <label class="layui-form-label">结束签退时间</label>
                <div class="layui-input-inline">
                    <input type="text" name="date" id="date" lay-verify="date" placeholder="yyyy-MM-dd" autocomplete="off" class="layui-input">
                </div>
            </div>
        </div>


        <div class="layui-form-item">
            <label class="layui-form-label">视频会议</label>
            <div class="layui-input-block">
                <input type="radio" name="sex" value="男" title="男" checked="">
                <input type="radio" name="sex" value="女" title="女">
                <input type="radio" name="sex" value="禁" title="禁用" disabled="">
            </div>
        </div>


        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">特殊说明</label>
            <div class="layui-input-block ">
                <textarea placeholder="请输入内容" class="layui-textarea block"></textarea>
            </div>
        </div>


        <div class="layui-form-item">
            <label class="layui-form-label">附件</label>
            <button type="button" class="layui-btn" id="test3"><i class="layui-icon"></i>上传文件</button>
        </div>
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button type="submit" class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>
@endsection
<script src="{{ asset('layui/layui.js') }}"></script>
<script>
    layui.use(['form', 'layedit', 'laydate','upload','transfer','util'], function(){
        var form = layui.form
            ,layer = layui.layer
            ,layedit = layui.layedit
            ,upload = layui.upload
            ,transfer = layui.transfer
            ,laydate = layui.laydate
            ,util = layui.util;

        //日期
        laydate.render({
            elem: '#time'
            ,change: function(value, date, endDate){
                console.log(value); //得到日期生成的值，如：2017-08-18
                console.log(date); //得到日期时间对象：{year: 2017, month: 8, date: 18, hours: 0, minutes: 0, seconds: 0}
                console.log(endDate); //得结束的日期时间对象，开启范围选择（range: true）才会返回。对象成员同上。
            }
        });
        laydate.render({
            elem: '#date1'
        });




        //模拟数据
        var data1 = [
            {"value": "1", "title": "李白"}
            ,{"value": "2", "title": "杜甫"}
            ,{"value": "3", "title": "苏轼"}
            ,{"value": "4", "title": "李清照"}
            ,{"value": "5", "title": "鲁迅", "disabled": true}
            ,{"value": "6", "title": "巴金"}
            ,{"value": "7", "title": "冰心"}
            ,{"value": "8", "title": "矛盾"}
            ,{"value": "9", "title": "贤心"}
        ]

            ,data2 = [
            {"value": "1", "title": "瓦罐汤"}
            ,{"value": "2", "title": "油酥饼"}
            ,{"value": "3", "title": "炸酱面"}
            ,{"value": "4", "title": "串串香", "disabled": true}
            ,{"value": "5", "title": "豆腐脑"}
            ,{"value": "6", "title": "驴打滚"}
            ,{"value": "7", "title": "北京烤鸭"}
            ,{"value": "8", "title": "烤冷面"}
            ,{"value": "9", "title": "毛血旺", "disabled": true}
            ,{"value": "10", "title": "肉夹馍"}
            ,{"value": "11", "title": "臊子面"}
            ,{"value": "12", "title": "凉皮"}
            ,{"value": "13", "title": "羊肉泡馍"}
            ,{"value": "14", "title": "冰糖葫芦", "disabled": true}
            ,{"value": "15", "title": "狼牙土豆"}
        ]




        //实例调用
        transfer.render({
            elem: '#test7'
            ,data: data1
            ,id: 'key123' //定义唯一索引
        });
        //批量办法定事件
        util.event('lay-demoTransferActive', {
            getData: function(othis){
                var getData = transfer.getData('key123'); //获取右侧数据
                layer.alert(JSON.stringify(getData));
            }
            ,reload:function(){
                //实例重载
                transfer.reload('key123', {
                    title: ['文人', '喜欢的文人']
                    ,value: ['2', '5', '9']
                    ,showSearch: true
                })
            }
        });











        //创建一个编辑器
        // var editIndex = layedit.build('LAY_demo_editor');

        //自定义验证规则
        // form.verify({
        //     title: function(value){
        //         if(value.length < 5){
        //             return '标题至少得5个字符啊';
        //         }
        //     }
        //     ,pass: [
        //         /^[\S]{6,12}$/
        //         ,'密码必须6到12位，且不能出现空格'
        //     ]
        //     ,content: function(value){
        //         layedit.sync(editIndex);
        //     }
        // });
        //
        // //监听指定开关
        // form.on('switch(switchTest)', function(data){
        //     layer.msg('开关checked：'+ (this.checked ? 'true' : 'false'), {
        //         offset: '6px'
        //     });
        //     layer.tips('温馨提示：请注意开关状态的文字可以随意定义，而不仅仅是ON|OFF', data.othis)
        // });

        //监听提交
        form.on('submit(demo1)', function(data){
            layer.alert(JSON.stringify(data.field), {
                title: '最终的提交信息'
            });
            $.ajax({
                type : 'post',
                url  : '/teacher/conference/add',
                data : data.field,
                success :function (re) {
                    console.log(re);
                },
                error :function (re) {
                    console.log(re);
                }

            });
            return false;
        });

        //表单赋值
        layui.$('#LAY-component-form-setval').on('click', function(){
            form.val('example', {
                "username": "贤心" // "name": "value"
                ,"password": "123456"
                ,"interest": 1
                ,"like[write]": true //复选框选中状态
                ,"close": true //开关状态
                ,"sex": "女"
                ,"desc": "我爱 layui"
            });
        });

        //表单取值
        layui.$('#LAY-component-form-getval').on('click', function(){
            var data = form.val('example');
            alert(JSON.stringify(data));
        });

        //指定允许上传的文件类型
        upload.render({
            elem: '#test3'
            ,url: '/upload/'
            ,accept: 'file' //普通文件
            ,done: function(res){
                console.log(res)
            }
        });

    });
</script>
