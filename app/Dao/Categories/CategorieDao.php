<?php


namespace App\Dao\Categories;

use App\Models\Categories\Categorie;

class CategorieDao
{

    public function getMyCategoriesByUserId($userId, $type)
    {
        $where =['owner_id' => $userId, 'type' => $type];
        return Categorie::where($where)->first();
    }


}
