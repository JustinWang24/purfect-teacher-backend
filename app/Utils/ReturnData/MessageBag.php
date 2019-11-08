<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 29/10/19
 * Time: 10:39 AM
 */

namespace App\Utils\ReturnData;


use App\Utils\JsonBuilder;

class MessageBag implements IMessageBag
{
    private $message;
    private $code;
    private $data;

    /**
     * MessageBag constructor.
     * @param int $code
     * @param string $message
     * @param null $data
     */
    public function __construct($code = JsonBuilder::CODE_SUCCESS, $message = '', $data = null)
    {
        $this->data = $data;
        $this->code = $code;
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message){
        $this->message = $message;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code){
        $this->code = $code;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data){
        $this->data = $data;
    }

    public function isSuccess()
    {
        return JsonBuilder::CODE_SUCCESS === $this->code || $this->code === true;
    }
}