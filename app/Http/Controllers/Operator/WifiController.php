<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Http\Requests\School\CampusRequest;
use App\Utils\FlashMessageBuilder;

class CampusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

}
