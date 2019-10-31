<?php


namespace App\BusinessLogic\DocumentsWorkflows\Contracts;


interface IPredefinedStep
{
    public function checkDocument($document);

    public function isDone();

    public function passToNext();
}
