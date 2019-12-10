<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 9/12/19
 * Time: 9:06 PM
 */

namespace App\Dao\Pipeline;


use App\Models\Pipeline\Flow\UserFlow;
use App\Utils\Pipeline\IUserFlow;

class UserFlowDao
{
    /**
     * @param $id
     * @return UserFlow
     */
    public function getById($id){
        return UserFlow::find($id);
    }

    /**
     * 更新
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data){
        return UserFlow::where('id',$id)->update($data);
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function terminate($id){
        $actions = (new ActionDao())->getHistoryByUserFlow($id, true);
        $this->update($id,['done'=>IUserFlow::TERMINATED]);
        return $actions;
    }
}