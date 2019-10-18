<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 18/10/19
 * Time: 10:48 PM
 */

namespace App\Http\Controllers\Operator;
use App\Http\Controllers\Controller;
use App\Http\Requests\School\InstituteRequest;

class InstitutesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit(InstituteRequest $request){

    }

    public function add(InstituteRequest $request){

    }

    public function update(InstituteRequest $request){

    }
}