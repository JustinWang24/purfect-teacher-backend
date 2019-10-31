<?php


namespace App\BusinessLogic\DocumentsWorkflows\Impl;


use App\BusinessLogic\DocumentsWorkflows\Contracts\IPredefinedStep;

class StepTwo extends AbstractStep
{
    public function checkDocument($document)
    {
        // TODO: Implement checkDocument() method.
    }

    public function passToNext()
    {
        $this->askForApprove();
        // TODO: Pass
    }

    public function log()
    {
        // TODO: Implement log() method.
    }
}
