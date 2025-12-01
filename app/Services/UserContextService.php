<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserContextService
{
    public static function roles(int $userId, ?string $module = null): Collection
    {
        return DB::table('user_roles')
            ->join('roles', 'roles.id', '=', 'user_roles.role_id')
            ->select([
                'roles.name as role',
                'roles.module',
                'user_roles.tenant_id',
                'user_roles.granted_at',
                'user_roles.expires_at',
            ])
            ->when($module, fn ($query) => $query->where('roles.module', $module))
            ->where('user_roles.user_id', $userId)
            ->whereNull('user_roles.deleted_at')
            ->orderBy('roles.module')
            ->orderBy('roles.name')
            ->get()
            ->map(function ($row) {
                $grantedAt = $row->granted_at ? Carbon::parse($row->granted_at) : null;
                $expiresAt = $row->expires_at ? Carbon::parse($row->expires_at) : null;

                return [
                    'role' => $row->role,
                    'module' => $row->module,
                    'tenant_id' => (int) $row->tenant_id,
                    'granted_at' => optional($grantedAt)->toDateTimeString(),
                    'expires_at' => optional($expiresAt)->toDateTimeString(),
                ];
            })
            ->values();
    }

    public static function tenants(int $userId): Collection
    {
        return DB::table('user_roles')
            ->join('tenants', 'tenants.tenant_id', '=', 'user_roles.tenant_id')
            ->select([
                'tenants.tenant_id',
                'tenants.nama',
                'tenants.kode',
            ])
            ->where('user_roles.user_id', $userId)
            ->whereNull('user_roles.deleted_at')
            ->distinct()
            ->orderBy('tenants.nama')
            ->get()
            ->map(function ($row) {
                return [
                    'tenant_id' => (int) $row->tenant_id,
                    'nama' => $row->nama,
                    'kode' => $row->kode,
                ];
            });
    }

    public static function roleSlugs(int $userId): array
    {
        return self::roles($userId)
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
    }
}


