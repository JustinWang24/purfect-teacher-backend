<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 18/12/19
 * Time: 9:33 PM
 */

namespace App\Dao\Schools;


use App\Models\Schools\YearManager;

class YearManagerDao
{
    public function get($schoolId, $year){
        return YearManager::where('school_id',$schoolId)->where('year',$year)->first();
    }

    public function save($data){
        if(isset($data['id'])){
            return $this->update($data);
        }
        else{
            return $this->create($data);
        }
    }

    public function create($data){
        return YearManager::create($data);
    }

    public function update($data){
        return YearManager::where('id',$data['id'])->update($data);
    }

    public function delete($id){
        return YearManager::where('id',$id)->delete();
    }

    public function getBySchool($schoolId){
        return YearManager::where('school_id',$schoolId)->get();
    }
}