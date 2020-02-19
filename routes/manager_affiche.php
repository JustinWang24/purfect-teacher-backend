<?php

Route::prefix('manager_affiche')->group(function ()
{
    // 社区
    Route::any('/affiche/affiche-pending-list','Affiche\AfficheController@affiche_pending_list')
        ->name('manager_affiche.affiche.affiche_pending_list'); // 待审核列表
    Route::any('/affiche/affiche-adopt-list','Affiche\AfficheController@affiche_adopt_list')
        ->name('manager_affiche.affiche.affiche_adopt_list'); // 已通过列表
    Route::any('/affiche/affiche-one','Affiche\AfficheController@affiche_one')
        ->name('manager_affiche.affiche.affiche_one');// 详情
    Route::any('/affiche/affiche-check-one','Affiche\AfficheController@affiche_check_one')
        ->name('manager_affiche.affiche.affiche_check_one');// 审核

    // 社群
    Route::any('/affiche/group-pending-list','Affiche\GroupController@group_pending_list')
        ->name('manager_affiche.group.group_pending_list'); // 待审核列表
    Route::any('/affiche/group-adopt-list','Affiche\GroupController@group_adopt_list')
        ->name('manager_affiche.group.group_adopt_list'); // 已通过列表
    Route::any('/affiche/group-one','Affiche\GroupController@group_one')
        ->name('manager_affiche.group.group_one');// 详情
    Route::any('/affiche/group-check-one','Affiche\GroupController@group_check_one')
        ->name('manager_affiche.affiche.group_check_one');// 审核


});
