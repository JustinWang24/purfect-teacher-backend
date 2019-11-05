<?php
namespace App\Dao\Textbook;

use App\Models\Schools\Textbook;

class TextbookDao
{

    /**
     * 创建或修改
     * @param $data
     * @return mixed
     */
    public function saveTextbook($data) {
        if(!empty($data['id'])) {
            $id = $data['id'];
            unset($data['id']);
            return Textbook::where('id',$id)->update($data);
        } else {
            return Textbook::create($data);
        }
    }
}
