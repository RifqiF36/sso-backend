<?php

namespace App\Http\Controllers;

use App\Services\UserContextService;
use Illuminate\Http\Request;

class AppSelectorController extends Controller
{
    public function index(Request $request)
    {
        $roleSlugs = UserContextService::roleSlugs($request->user()->id);

        $apps = collect(config('role_apps', []))
            ->filter(function ($appConfig) use ($roleSlugs) {
                $allowedRoles = collect($appConfig['roles'] ?? []);
                if ($allowedRoles->isEmpty() || $allowedRoles->contains('*')) {
                    return true;
                }

                return $allowedRoles->intersect($roleSlugs)->isNotEmpty();
            })
            ->map(function ($appConfig, $key) {
                return [
                    'code' => $key,
                    'name' => $appConfig['name'] ?? $key,
                    'url' => $appConfig['url'] ?? '#',
                    'description' => $appConfig['description'] ?? null,
                    'icon' => $appConfig['icon'] ?? null,
                ];
            })
            ->values()
            ->all();

        return response()->json(['apps' => $apps]);
    }
}

