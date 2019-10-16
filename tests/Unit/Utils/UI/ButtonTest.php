<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 16/10/19
 * Time: 1:55 PM
 */

namespace Tests\Unit\Utils\UI;
use App\Utils\UI\Button;
use Tests\TestCase;

class ButtonTest extends TestCase
{
    /**
     * 测试普通圆角按钮
     */
    public function testItCanPrintRoundButton(){
        // 测试文字按钮的输出
        $normalButton='<button id="button" class="btn btn-round btn-primary custom">Primary</button>';
        $btn1 = Button::Print(
            ['id'=>'button','class'=>'custom','text'=>'Primary'],
            Button::TYPE_PRIMARY, false, true
        );
        $this->assertEquals($normalButton, $btn1);

        // 测试图标按钮的输出
        $iconButton='<button id="button" class="btn btn-round btn-danger custom"><i class="fa fa-thumbs-up"></i></button>';
        $btn2 = Button::Print(
            ['id'=>'button','class'=>'custom','icon'=>'thumbs-up'],
            Button::TYPE_DANGER, true, true
        );
        $this->assertEquals($iconButton, $btn2);

        // 测试图标 + 文字 按钮的输出
        $iconAndTextButton='<button id="button" class="btn btn-round btn-default custom"><i class="fa fa-thumbs-up"></i>Primary</button>';
        $btn3 = Button::Print(
            ['id'=>'button','class'=>'custom','text'=>'Primary'],
            Button::TYPE_DEFAULT, 'thumbs-up', true
        );
        $this->assertEquals($iconAndTextButton, $btn3);
    }

    /**
     * 测试da圆角按钮
     */
    public function testItCanPrintCircleButton(){
        // 测试文字按钮的输出
        $normalButton='<button id="button" class="btn btn-circle btn-primary custom">Primary</button>';
        $btn1 = Button::PrintCircle(
            ['id'=>'button','class'=>'custom','text'=>'Primary'],
            Button::TYPE_PRIMARY, false, true
        );
        $this->assertEquals($normalButton, $btn1);

        // 测试图标按钮的输出
        $iconButton='<button id="button" class="btn btn-circle btn-danger custom"><i class="fa fa-thumbs-up"></i></button>';
        $btn2 = Button::PrintCircle(
            ['id'=>'button','class'=>'custom','icon'=>'thumbs-up'],
            Button::TYPE_DANGER, true, true
        );
        $this->assertEquals($iconButton, $btn2);

        // 测试图标 + 文字 按钮的输出
        $iconAndTextButton='<button id="button" class="btn btn-circle btn-default custom"><i class="fa fa-thumbs-up"></i>Primary</button>';
        $btn3 = Button::PrintCircle(
            ['id'=>'button','class'=>'custom','text'=>'Primary'],
            Button::TYPE_DEFAULT, 'thumbs-up', true
        );
        $this->assertEquals($iconAndTextButton, $btn3);
    }

    /**
     * 测试打印按钮组
     */
    public function testItCanPrintButtonGroup(){
        $btn = Button::PrintGroup([
            'id'=>'haha',
            'class'=>'hahaha',
            'text'=>'test',
            'subs'=>[
                ['url'=>'lalla','text'=>'lalala'],
                ['url'=>'lalla2','text'=>'lalala2'],
            ]
        ],Button::TYPE_DANGER, true);

        $expect = '<div class="btn-group">
    <button type="button" class="btn btn-danger  test">test</button>
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
        <i class="fa fa-angle-down"></i>
    </button>
    <ul class="dropdown-menu" role="menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(79px, 39px, 0px); top: 0px; left: 0px; will-change: transform;">
                    <li><a href="lalla" target="_blank">lalala</a></li>
                    <li><a href="lalla2" target="_blank">lalala2</a></li>
            </ul>
</div>';

        $this->assertEquals($expect, $btn);
    }
}