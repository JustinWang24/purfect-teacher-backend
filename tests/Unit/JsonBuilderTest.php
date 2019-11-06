<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 6/11/19
 * Time: 3:39 PM
 */

namespace Tests\Unit;
use App\User;
use App\Utils\JsonBuilder;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class JsonBuilderTest extends TestCase
{
    /**
     * 确保可以正确的转换 null 为 空字符串
     */
    public function testItCanTurnNullToEmptyString(){
        $arr = [
            'a'=>'abc',
            'b'=>null
        ];
        $arr = JsonBuilder::TransformNullToEmptyString($arr);
        $this->assertNotNull($arr['b']);

        // 多维数组
        $arr2 = [
            'a'=>'abc',
            'b'=>[
                'c'=>123,
                'd'=>null
            ]
        ];
        $arr2 = JsonBuilder::TransformNullToEmptyString($arr2);
        $this->assertNotNull($arr2['b']['d']);

        // 对象
        $user = User::find(1);
        try{
            $arr3 = JsonBuilder::TransformNullToEmptyStringForObject($user);
            $this->assertNotNull($arr3['email']);
        }catch (\Exception $exception){
            dd($exception->getMessage());
        }

        $arr4 = [
            'a'=>[
                'c'=>456,
                'd'=>null
            ],
            'b'=>[
                'c'=>123,
                'd'=>null,
                'e'=>[
                    'f'=>789,
                    'g'=>null
                ]
            ]
        ];
        $cols = new Collection($arr4);

        try{
            $arr5 = JsonBuilder::TransformNullToEmptyStringForObject($cols);
            $this->assertNotNull($arr5['a']['d']);
            $this->assertNotNull($arr5['b']['d']);
            $this->assertNotNull($arr5['b']['e']['g']);
        }catch (\Exception $exception){
            dd($exception->getMessage());
        }
    }

    public function testItCanOutputJsonCorrectly(){
        $arr = [
            'a'=>'abc',
            'b'=>null
        ];
        $str = JsonBuilder::Success($arr);
        $this->assertEquals('{"code":1000,"message":"OK","data":{"a":"abc","b":""}}', $str);
    }
}