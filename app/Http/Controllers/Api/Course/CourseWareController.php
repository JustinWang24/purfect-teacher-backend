<?php


namespace App\Http\Controllers\Api\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\School\CourseRequest;
use App\Utils\JsonBuilder;

class CourseWareController extends Controller
{

    /**
     * 课件
     * @param CourseRequest $request
     * @return string
     */
    public function index(CourseRequest $request)
    {
        // todo ::教师端课件
        return JsonBuilder::Success();
    }

}
