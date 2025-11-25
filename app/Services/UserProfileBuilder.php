<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;

class UserProfileBuilder
{
    public static function build(User $user): array
    {
        $user->loadMissing('profile');

        $roles = UserContextService::roles($user->id)->all();
        $tenants = UserContextService::tenants($user->id)->all();
        $roleSlugs = collect($roles)
            ->pluck('role')
            ->map(function ($role) {
                $role = $role ?? '';
                $role = trim($role);

                if ($role === '') {
                    return null;
                }

                return Str::slug($role, '_');
            })
            ->filter()
            ->unique()
            ->values()
            ->all();

        $appsCollection = collect(AppCatalogService::forRoleSlugs($roleSlugs));
        $profile = $user->profile;

        $profilePayload = $profile ? [
            'nip' => $profile->nip,
            'gender' => $profile->gender,
            'unit_kerja' => $profile->unit_kerja,
            'asal_dinas' => $profile->asal_dinas,
        ] : null;

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $roles,
            'role_slugs' => $roleSlugs,
            'tenants' => $tenants,
            'apps' => $appsCollection->all(),
            'default_app_routes' => $appsCollection
                ->mapWithKeys(fn ($app) => [$app['code'] => $app['default_route']])
                ->all(),
            'profile' => $profilePayload,
        ];
    }
}

