<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 11/11/19
 * Time: 7:38 PM
 */

namespace App\Utils\Misc\Contracts;


interface ISmsSender
{
    /**
     * @param string $mobile
     * @param string $templateId
     * @param array $data
     * @return bool
     */
    public function send($mobile,$templateId,$data): bool;
}