<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 8/12/19
 * Time: 2:58 PM
 */

namespace App\Utils\Pipeline;


interface IUser
{
    public function getId(): int;
    public function getUuid(): string;
    public function getApiToken(): string;
    public function getName(): string;

    /**
     * 获取用户的类型, 用来和流程的流程做匹配用
     * @return int
     */
    public function getType(): int;
}