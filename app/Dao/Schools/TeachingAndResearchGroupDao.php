<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 20/12/19
 * Time: 1:30 PM
 */

namespace App\Dao\Schools;


use App\Models\Schools\TeachingAndResearchGroup;
use App\Models\Schools\TeachingAndResearchGroupMember;
use Illuminate\Support\Facades\DB;

class TeachingAndResearchGroupDao
{
    public function getById($id){
        return TeachingAndResearchGroup::find($id);
    }

    public function getAllBySchool($schoolId){
        return TeachingAndResearchGroup::where('school_id',$schoolId)->with('members')->get();
    }

    public function create($data){
        return TeachingAndResearchGroup::create($data);
    }

    public function update($id, $data){
        return TeachingAndResearchGroup::where('id',$id)->update($data);
    }

    public function save($data){
        if(empty($data['id'])){
            return $this->create($data);
        }
        else{
            return $this->update($data['id'], $data);
        }
    }

    public function delete($id){
        DB::beginTransaction();
        $result = false;
        try{
            TeachingAndResearchGroup::where('id',$id)->delete();
            TeachingAndResearchGroupMember::where('group_id',$id)->delete();
            DB::commit();
            $result = true;
        }
        catch (\Exception $exception){
            DB::rollBack();
        }
        return $result;
    }

    public function saveMembers($members){
        foreach ($members as $member) {
            if(!isset($member['id'])){
                $this->addMember($member);
            }
        }
        return true;
    }

    public function addMember($data){
        return TeachingAndResearchGroupMember::create($data);
    }

    public function deleteMember($memberId){
        return TeachingAndResearchGroupMember::where('id',$memberId)->delete();
    }
}