<?php

Route::prefix('manager_affiche')->group(function ()
{
    // 置顶推荐
    Route::any('/affiche/top-affiche-list','Affiche\AfficheController@top_affiche_list')
        ->name('manager_affiche.affiche.top_affiche_list'); // 置顶列表
    Route::any('/affiche/top-affiche-add','Affiche\AfficheController@top_affiche_add')
        ->name('manager_affiche.affiche.top_affiche_add'); // 添加置顶
    Route::any('/affiche/top-affiche-delete','Affiche\AfficheController@top_affiche_delete')
        ->name('manager_affiche.affiche.top_affiche_delete'); // 置顶删除
    Route::any('/affiche/top-affiche-sort','Affiche\AfficheController@top_affiche_sort')
        ->name('manager_affiche.affiche.top_affiche_sort'); // 置顶排序

    // 社区
    Route::any('/affiche/affiche-pending-list','Affiche\AfficheController@affiche_pending_list')
        ->name('manager_affiche.affiche.affiche_pending_list'); // 待审核列表
    Route::any('/affiche/affiche-adopt-list','Affiche\AfficheController@affiche_adopt_list')
        ->name('manager_affiche.affiche.affiche_adopt_list'); // 已通过列表
    Route::any('/affiche/affiche-one','Affiche\AfficheController@affiche_one')
        ->name('manager_affiche.affiche.affiche_one');// 详情
    Route::any('/affiche/affiche-check-one','Affiche\AfficheController@affiche_check_one')
        ->name('manager_affiche.affiche.affiche_check_one');// 审核
    Route::any('/affiche/affiche-comment-list','Affiche\AfficheController@affiche_comment_list')
        ->name('manager_affiche.affiche.affiche_comment_list'); // 动态评论列表
    Route::any('/affiche/affiche-praise-list','Affiche\AfficheController@affiche_praise_list')
        ->name('manager_affiche.affiche.affiche_praise_list'); // 动态点赞列表
    Route::any('/affiche/affiche-view-list','Affiche\AfficheController@affiche_view_list')
        ->name('manager_affiche.affiche.affiche_view_list'); // 动态浏览总数

    // 组织
    Route::any('/affiche/group-pending-list','Affiche\GroupController@group_pending_list')
        ->name('manager_affiche.group.group_pending_list'); // 待审核列表
    Route::any('/affiche/group-adopt-list','Affiche\GroupController@group_adopt_list')
        ->name('manager_affiche.group.group_adopt_list'); // 已通过列表
    Route::any('/affiche/group-one','Affiche\GroupController@group_one')
        ->name('manager_affiche.group.group_one');// 详情
    Route::any('/affiche/group-check-one','Affiche\GroupController@group_check_one')
        ->name('manager_affiche.affiche.group_check_one');// 审核
    Route::any('/affiche/group-notice-list','Affiche\GroupController@group_notice_list')
        ->name('manager_affiche.group.group_notice_list');// 组织公告列表
    Route::any('/affiche/group-member-list','Affiche\GroupController@group_member_list')
        ->name('manager_affiche.group.group_member_list');// 组织成员列表
    Route::any('/affiche/group-affiche-list','Affiche\GroupController@group_affiche_list')
        ->name('manager_affiche.group.group_affiche_list'); // 组织动态列表
    Route::any('/affiche/group-affiche-one','Affiche\GroupController@group_affiche_one')
        ->name('manager_affiche.group.group_affiche_one'); // 组织动态详情
});
