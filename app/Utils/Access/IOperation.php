<?php
/**
 * 可以执行的操作
 */

namespace App\Utils\Access;

interface IOperation
{
    const CREATE    = 1; // 增
    const RETRIEVE  = 2; // 查
    const UPDATE    = 3; // 改
    const DELETE    = 4; // 删
}