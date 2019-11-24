<?php


namespace App\BusinessLogic\DocumentsWorkflows\Impl;

use App\BusinessLogic\DocumentsWorkflows\Contracts\IPredefinedStep;

abstract class AbstractStep implements IPredefinedStep
{
    protected $done = false;

    protected $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

//    public function checkDocument($document)
//    {
//        // TODO: Implement checkDocument() method.
//    }
//
    public function isDone()
    {
        $this->log();
        return $this->done;
    }
//
//    public function passToNext()
//    {
//        // TODO: Implement passToNext() method.
//    }

    public function askForApprove(){

    }

    public abstract function log();
}
