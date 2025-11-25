<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Services\UserContextService;
use App\Services\UserProfileBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class StaffUserController extends Controller
{
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

