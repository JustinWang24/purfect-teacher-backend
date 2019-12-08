<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 1/12/19
 * Time: 2:02 PM
 */

namespace App\Dao\Performance;


use App\Models\Teachers\Performance\TeacherPerformanceConfig;

class TeacherPerformanceConfigDao
{
    public function getById($id){
        return TeacherPerformanceConfig::find($id);
    }

    public function create($data){
        return TeacherPerformanceConfig::create($data);
    }

    public function delete($id){
        return TeacherPerformanceConfig::where('id',$id)->delete();
    }

    public function update($data){
        return TeacherPerformanceConfig::where('id',$data['id'])->update($data);
    }
}