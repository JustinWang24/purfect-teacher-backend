<?php
/**
 * 可以被持久化的接口
 * User: justinwang
 * Date: 2/12/19
 * Time: 1:03 PM
 */

namespace App\Utils\Pipeline;


interface IPersistent
{
    public function save();

    public function update();

    public function delete();
}