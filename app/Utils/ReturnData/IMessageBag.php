<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 29/10/19
 * Time: 7:51 AM
 */

namespace App\Utils\ReturnData;


interface IMessageBag
{
    /**
     * @return string
     */
    public function getMessage();

    /**
     * @return int|null
     */
    public function getCode();

    /**
     * @return mixed
     */
    public function getData();

    /**
     * @return boolean
     */
    public function isSuccess();
}