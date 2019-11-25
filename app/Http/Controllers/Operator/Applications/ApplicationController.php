<?php
namespace App\Http\Controllers\Operator\Applications;

use App\Http\Controllers\Controller;

class ApplicationController extends Controller
{
    public function list() {

        return view('school_manager.application.list', $this->dataForView);
    }
}
