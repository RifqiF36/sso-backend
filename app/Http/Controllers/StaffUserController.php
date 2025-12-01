<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Services\UserContextService;
use App\Services\UserProfileBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class StaffUserController extends Controller
{
    public function index(Request $request)
    {
        $actor = $request->user();
        $roleSlugs = UserContextService::roleSlugs($actor->id);

        if (!in_array('staff', $roleSlugs, true)) {
            abort(403, 'Hanya staff yang dapat melihat daftar akun.');
        }

        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ]);

        $perPage = (int) ($validated['per_page'] ?? 10);
        $page = (int) ($validated['page'] ?? 1);
        $tenantIds = UserContextService::tenants($actor->id)->pluck('tenant_id');

        if ($tenantIds->isEmpty()) {
            return response()->json([
                'data' => [],
                'meta' => [
                    'current_page' => 1,
                    'last_page' => 1,
                    'per_page' => $perPage,
                    'total' => 0,
                ],
            ]);
        }

        $query = User::query()
            ->select('users.*')
            ->join('user_roles', function ($join) use ($tenantIds) {
                $join->on('user_roles.user_id', '=', 'users.id')
                    ->whereIn('user_roles.tenant_id', $tenantIds)
                    ->where('user_roles.module', 'asset_risk')
                    ->whereNull('user_roles.deleted_at');
            })
            ->whereNull('users.deleted_at')
            ->with('profile')
            ->distinct();

        if (!empty($validated['search'])) {
            $search = $validated['search'];
            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('users.name', 'like', '%' . $search . '%')
                    ->orWhere('users.email', 'like', '%' . $search . '%')
                    ->orWhereHas('profile', function ($profileQuery) use ($search) {
                        $profileQuery->where('user_profiles.nip', 'like', '%' . $search . '%');
                    });
            });
        }

        $paginator = $query
            ->orderBy('users.name')
            ->paginate($perPage, ['users.*'], 'page', $page);

        $data = $paginator->getCollection()->map(function (User $user) {
            $roles = UserContextService::roles($user->id)->all();
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

            $profile = $user->profile;

            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $roles,
                'role_slugs' => $roleSlugs,
                'profile' => $profile ? [
                    'nip' => $profile->nip,
                    'gender' => $profile->gender,
                    'unit_kerja' => $profile->unit_kerja,
                    'asal_dinas' => $profile->asal_dinas,
                ] : null,
                'created_at' => optional($user->created_at)->toDateTimeString(),
                'updated_at' => optional($user->updated_at)->toDateTimeString(),
            ];
        });

        return response()->json([
            'data' => $data,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $actor = $request->user();
        $roleSlugs = UserContextService::roleSlugs($actor->id);

        if (!in_array('staff', $roleSlugs, true)) {
            abort(403, 'Hanya staff yang dapat menambahkan akun.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'string', 'max:100'],
            'nip' => ['nullable', 'string', 'max:100'],
            'gender' => ['nullable', 'string', 'max:50'],
            'unit_kerja' => ['nullable', 'string', 'max:255'],
            'asal_dinas' => ['nullable', 'string', 'max:255'],
        ]);

        $tenant = UserContextService::tenants($actor->id)->first();
        if (!$tenant) {
            throw ValidationException::withMessages([
                'tenant' => ['Staff tidak memiliki tenant aktif untuk menambahkan user.'],
            ])->status(422);
        }

        $module = 'asset_risk';

        $user = DB::transaction(function () use ($data, $tenant, $module) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $user->profile()->create([
                'nip' => $data['nip'] ?? null,
                'gender' => $data['gender'] ?? null,
                'unit_kerja' => $data['unit_kerja'] ?? null,
                'asal_dinas' => $data['asal_dinas'] ?? null,
            ]);

            $role = Role::firstOrCreate(
                ['name' => $data['role'], 'module' => $module],
                ['description' => ucwords(str_replace('_', ' ', $data['role']))]
            );

            DB::table('user_roles')->updateOrInsert(
                [
                    'user_id' => $user->id,
                    'role_id' => $role->role_id,
                    'tenant_id' => $tenant['tenant_id'],
                    'module' => $module,
                ],
                [
                    'granted_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                    'deleted_at' => null,
                ]
            );

            return $user;
        });

        return response()->json([
            'message' => 'Akun baru berhasil dibuat.',
            'user' => UserProfileBuilder::build($user),
        ], 201);
    }
}
