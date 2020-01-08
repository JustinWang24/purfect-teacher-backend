<?php
   namespace App\Http\Controllers\Operator;

   use Illuminate\Http\Request;
   use Illuminate\Support\Facades\DB;
   use App\Http\Controllers\Controller;

   class WifiApiController extends Controller
   {
      public function __construct()
      {
         $this->middleware('auth');
      }
      /**
       * Func 获取学校和校区
       * @param Request $request
       * @return Json
       */
      public function get_school_campus(Request $request)
      {
         $oneCategory = DB::table('schools')->get ( ['id','name'])->toArray ();

         $oneCategoryIds = array();
         $list = array();

         if(!empty($oneCategory) && count ($oneCategory) > 0)
         {
            foreach ($oneCategory as $cv)
            {
               $oneCategoryIds[] = $cv->id;
               $list['0'][$cv->id] = $cv->name;
            }
         }

         // 获取校区
         $twoCategory = DB::table('campuses')->get ( ['id','school_id','name'])->toArray ();
         if(!empty($twoCategory) && count ($twoCategory) > 0)
         {
            foreach ($twoCategory as $cyv)
            {
               $list['0,'.$cyv->school_id][$cyv->id] = $cyv->name;
            }
         }
         echo 'var itemall = '.json_encode($list);exit;
      }

      /**
       * Func 获取分类
       * @param Request $request
       * @return Json
       */
      public function get_issue_types(Request $request)
      {
         $oneCategory = DB::table('wifi_issue_types')
                          ->where([['type_pid','=',0],['purpose','=',2]])
                          ->get ( ['typeid as id','type_name as name'])
                          ->toArray ();

         $list = array();
         $oneCategoryIds = array();
         if(!empty($oneCategory) && count ($oneCategory) > 0)
         {
            foreach ($oneCategory as $cv)
            {
               $oneCategoryIds[] = $cv->id;
               $list['0'][$cv->id] = $cv->name;
            }
         }
         // 获取二级
         $twoCategory = DB::table('wifi_issue_types')
                           ->where([['type_pid','>',0],['purpose','=',2]])
                           ->get ( ['typeid as id','type_pid','type_name as name'])
                           ->toArray ();
         if(!empty($twoCategory) && count ($twoCategory) > 0)
         {
            foreach ($twoCategory as $cyv)
            {
               $list['0,'.$cyv->type_pid][$cyv->id] = $cyv->name;
            }
         }
         echo 'var itemall = '.json_encode($list);exit;
      }

   }