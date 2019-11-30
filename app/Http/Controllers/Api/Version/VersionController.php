<?php

namespace App\Http\Controllers\Api\Version;

use App\Dao\Version\VersionDao;
use App\Utils\JsonBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VersionController extends Controller
{
    public function index()
    {
        $dao = new VersionDao();
        return JsonBuilder::Success($dao->getLastVersion());
    }
}
