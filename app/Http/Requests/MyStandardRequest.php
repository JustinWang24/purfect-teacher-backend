<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 17/10/19
 * Time: 11:49 AM
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class MyStandardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * 获取系统允许的所有操作
     * @return \Illuminate\Config\Repository|mixed
     */
    public function getAllActions(){
        return config('acl.actions');
    }

    /**
     * 所有被禁止的权限的 slug
     * @return array
     */
    protected function slugOfDisallowAll(){
        $slug = [];
        $actions = $this->getAllActions();
        foreach ($actions as $action) {
            $slug[$action] = false;
        }
        return $slug;
    }

    /**
     * 获取当前操作的学校的 ID
     * @return mixed
     */
    public function getSchoolId(){
        return $this->session()->get('school.id',null);
    }

    /**
     * 获取参数中的 uuid
     * @return string
     */
    public function uuid(){
        return $this->get('uuid',null);
    }

    /**
     * 获取页码
     * @return int
     */
    public function getPageNumber(){
        return $this->has('pageNumber') ? intval($this->get('pageNumber')) : 0;
    }

    /**
     * 获取分页大小
     * @return int
     */
    public function getPageSize(){
        return $this->has('pageSize') ? intval($this->get('pageSize')) : 20;
    }

    /**
     * 获取提交的版本信息
     * @return string
     */
    public function getVersion(){
        return $this->has('version') ? $this->get('version') : null;
    }

    /**
     * 获取专业ID
     */
    public function getMajorId() {
        return $this->get('major_id','');
    }


    /**
     * 获取班级ID
     * @return mixed
     */
    public function getGradeId() {
        return $this->get('grade_id',null);
    }


    /**
     * 获取校区ID
     * @return mixed
     */
    public function getCampusId() {
        return $this->get('campus_id',null);
    }


    /**
     * 获取课程ID
     * @return mixed
     */
    public function getCourseId() {
        return $this->get('course_id',null);
    }


    /**
     * 获取教材ID
     * @return mixed
     */
    public function getTextbookId() {
        return $this->get('textbook_id', null);
    }
}
