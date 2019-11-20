<?php


namespace App\Utils\Misc\Contracts;

use App\Utils\ReturnData\IMessageBag;

interface JPushSender
{


    /**
     * @param  object $user
     * @return mixed
     */
    public function user($user);

    /**
     * @param $user
     * @param string $title
     * @param string $content
     * @param array $extras
     * @return IMessageBag
     */
    public function send($user, $title, $content, $extras): IMessageBag;

}
