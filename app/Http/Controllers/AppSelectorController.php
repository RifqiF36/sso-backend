<?php

namespace App\Http\Controllers;

use App\Services\AppCatalogService;
use App\Services\UserContextService;
use Illuminate\Http\Request;

class AppSelectorController extends Controller
{
    public function index(Request $request)
    {
        $roleSlugs = UserContextService::roleSlugs($request->user()->id);

        $apps = AppCatalogService::forRoleSlugs($roleSlugs);

        return response()->json(['apps' => $apps]);
    }
}

