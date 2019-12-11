<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 16/10/19
 * Time: 9:45 PM
 */

namespace Tests\Unit\Utils\UI;
use Tests\TestCase;
use App\Utils\UI\Button;
use App\Utils\UI\Anchor;

class AnchorTest extends TestCase
{
    /**
     * 测试普通圆角按钮
     */
    public function testItCanPrintRoundButton(){
        // 测试文字按钮的输出
        $normalButton='<a href="a?b=c" id="button" class="btn btn-round btn-primary custom">Primary</a>';
        $btn1 = Anchor::Print(
            ['id'=>'button','class'=>'custom','text'=>'Primary','href'=>'a','params'=>['b'=>'c']],
            Button::TYPE_PRIMARY, false, true
        );
        $this->assertEquals($normalButton, $btn1);

        // 测试图标按钮的输出
        $iconButton='<a href="a?b=c" id="button" class="btn btn-round btn-danger custom"><i class="fa fa-thumbs-up"></i></a>';
        $btn2 = Anchor::Print(
            ['id'=>'button','class'=>'custom','icon'=>'thumbs-up','href'=>'a','params'=>['b'=>'c']],
            Button::TYPE_DANGER, true, true
        );
        $this->assertEquals($iconButton, $btn2);

        // 测试图标 + 文字 按钮的输出
        $iconAndTextButton='<a href="a?b=c" id="button" class="btn btn-round btn-default custom"><i class="fa fa-thumbs-up"></i>Primary</a>';
        $btn3 = Anchor::Print(
            ['id'=>'button','class'=>'custom','text'=>'Primary','href'=>'a','params'=>['b'=>'c']],
            Button::TYPE_DEFAULT, 'thumbs-up', true
        );
        $this->assertEquals($iconAndTextButton, $btn3);
    }

    /**
     * 测试da圆角按钮
     */
    public function testItCanPrintCircleButton(){
        // 测试文字按钮的输出
        $normalButton='<a href="a?b=c" id="button" class="btn btn-circle btn-primary custom">Primary</a>';
        $btn1 = Anchor::PrintCircle(
            ['id'=>'button','class'=>'custom','text'=>'Primary','href'=>'a','params'=>['b'=>'c']],
            Button::TYPE_PRIMARY, false, true
        );
        $this->assertEquals($normalButton, $btn1);

        // 测试图标按钮的输出
        $iconButton='<a href="a?b=c" id="button" class="btn btn-circle btn-danger custom"><i class="fa fa-thumbs-up"></i></a>';
        $btn2 = Anchor::PrintCircle(
            ['id'=>'button','class'=>'custom','icon'=>'thumbs-up','href'=>'a','params'=>['b'=>'c']],
            Button::TYPE_DANGER, true, true
        );
        $this->assertEquals($iconButton, $btn2);

        // 测试图标 + 文字 按钮的输出
        $iconAndTextButton='<a href="a?b=c" id="button" class="btn btn-circle btn-default custom"><i class="fa fa-thumbs-up"></i>Primary</a>';
        $btn3 = Anchor::PrintCircle(
            ['id'=>'button','class'=>'custom','text'=>'Primary','href'=>'a','params'=>['b'=>'c']],
            Button::TYPE_DEFAULT, 'thumbs-up', true
        );
        $this->assertEquals($iconAndTextButton, $btn3);
    }

}