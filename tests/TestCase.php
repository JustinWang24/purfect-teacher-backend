<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * 利用 Factory 来生成 mock 数据的方法
     * @param $modelClassName
     * @param bool $asArray
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\FactoryBuilder|array
     */
    protected function _createMockData($modelClassName, $asArray = false, $attributes = []){
        $builder= factory($modelClassName);
        if($asArray){
            return $builder->make($attributes)->toArray();
        }
        return $builder;
    }
}
