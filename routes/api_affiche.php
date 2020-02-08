<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('api_affiche')->middleware('auth:api')->group(function () {
    // 首页动态接口
    Route::any('/v1/index/base-info','\App\Http\Controllers\Api\Affiche\IndexController@base_info');
    // 首页动态接口
    Route::any('/v1/index/index-info','\App\Http\Controllers\Api\Affiche\IndexController@index_info');
    // 添加动态接口
    Route::any('/v1/affiche/add-affiche-info','\App\Http\Controllers\Api\Affiche\AfficheController@add_affiche_info');
    // 全部和本校动态列表
    Route::any('/v1/affiche/affiche-list-info','\App\Http\Controllers\Api\Affiche\AfficheController@affiche_list_info');
    // 全部和本校动态详情
    Route::any('/v1/affiche/affiche-one-info','\App\Http\Controllers\Api\Affiche\AfficheController@affiche_one_info');
    // 动态评论和评论互评接口
    Route::any('/v1/message/message-add-info','\App\Http\Controllers\Api\Affiche\MessageController@message_add_info');
    // 删除动态评论接口
    Route::any('/v1/message/message-del-info','\App\Http\Controllers\Api\Affiche\MessageController@message_del_info');
    // 动态点赞和互评点赞接口
    Route::any('/v1/praise/praise-add-info','\App\Http\Controllers\Api\Affiche\PraiseController@praise_add_info');
    // 用户关注和取消关注接口
    Route::any('/v1/follow/follow-edit-info','\App\Http\Controllers\Api\Affiche\FollowController@follow_edit_info');
    // 我的消息列表接口
    Route::any('/v1/message/message-list-info','\App\Http\Controllers\Api\Affiche\MessageController@message_list_info');
    // 我的消息删除接口
    Route::any('/v1/message/message-reset-info','\App\Http\Controllers\Api\Affiche\MessageController@message_reset_info');

    //------------------------------------------------------学生会+社团--------------------------------------------------
    // 添加群接口
    Route::any('/v1/group/add-group-info','\App\Http\Controllers\Api\Affiche\GroupController@add_group_info');
    // 群组待审核接口
    Route::any('/v1/group/get-check-group-one-info','\App\Http\Controllers\Api\Affiche\GroupController@get_check_group_one_info');
    // 我的群组列表接口
    Route::any('/v1/group/get-group-list-info','\App\Http\Controllers\Api\Affiche\GroupController@get_group_list_info');
    // 我的群组详情接口
    Route::any('/v1/group/get-group-one-info','\App\Http\Controllers\Api\Affiche\GroupController@get_group_one_info');
    // 加入群组接口
    Route::any('/v1/group/join-group-info','\App\Http\Controllers\Api\Affiche\GroupController@join_group_info');
    // 获取群组所有用户接口
    Route::any('/v1/group/get-group-more-member-list-info','\App\Http\Controllers\Api\Affiche\GroupController@get_group_more_member_list_info');
    // 获取群组已通过用户接口
    Route::any('/v1/group/get-group-passed-member-list-info','\App\Http\Controllers\Api\Affiche\GroupController@get_group_passed_member_list_info');
    // 获取群组待审核用户接口
    Route::any('/v1/group/get-group-pending-member-list-info','\App\Http\Controllers\Api\Affiche\GroupController@get_group_pending_member_list_info');
    // 审核单个用户加入群组接口
    Route::any('/v1/group/edit-group-member-info','\App\Http\Controllers\Api\Affiche\GroupController@edit_group_member_info');
    // 删除群组中单个用户接口
    Route::any('/v1/group/del-group-member-info','\App\Http\Controllers\Api\Affiche\GroupController@del_group_member_info');

    //-------------------------------------------------(学生会+社团)+动态------------------------------------------------
    // 群组添加动态接口
    Route::any('/v1/groupaffiche/add-groupaffiche-info','\App\Http\Controllers\Api\Affiche\GroupafficheController@add_groupaffiche_info');
    // 群组动态列表接口
    Route::any('/v1/groupaffiche/get-groupaffiche-list-info','\App\Http\Controllers\Api\Affiche\GroupafficheController@get_groupaffiche_list_info');

    //-------------------------------------------------(学生会+社团)+公告------------------------------------------------未写完----
    // 添加公告接口
    Route::any('/v1/groupnotices/add-groupnotices-info','\App\Http\Controllers\Api\Affiche\GroupnoticesController@add_groupnotices_info');
    // 公告列表接口
    Route::any('/v1/groupnotices/list-groupnotices-info','\App\Http\Controllers\Api\Affiche\GroupnoticesController@list_groupnotices_info');
    // 公告详情接口
    Route::any('/v1/groupnotices/one-groupnotices-info','\App\Http\Controllers\Api\Affiche\GroupnoticesController@one_groupnotices_info');
    // 公告公告已读和未读接口
    Route::any('/v1/groupnotices/unreadOrread-groupnotices-info','\App\Http\Controllers\Api\Affiche\GroupnoticesController@unreadOrread_groupnotices_info');
});
