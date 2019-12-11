<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 18/10/19
 * Time: 10:17 PM
 */

namespace App\Http\Controllers\Operator;
use App\Dao\Users\UserDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StudentRequest;

class StudentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function add(){
        return 'student add';
    }

    public function suspend(StudentRequest $request){
        return 'student suspend';
    }

    public function stop(StudentRequest $request){
        return 'student stop';
    }

    public function reject(StudentRequest $request){
        return 'student reject';
    }

    public function update(StudentRequest $request){

    }
}