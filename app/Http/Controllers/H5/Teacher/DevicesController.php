<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 23/12/19
 * Time: 4:07 PM
 */

namespace App\Http\Controllers\H5\Teacher;
use App\Http\Controllers\Controller;
use App\Models\Schools\Facility;
use Illuminate\Http\Request;

class DevicesController extends Controller
{
    /**
     * @param Request $request
     */
    public function devices(Request $request){
        $location = $request->get('location', Facility::LOCATION_INDOOR);
    }
}