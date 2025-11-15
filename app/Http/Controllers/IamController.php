<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

class IamController extends Controller
{
    public function roles(Request $request)
    {
        $request->validate(['module' => 'required|string']);
        $module = $request->query('module');

        $rows = DB::table('roles')
            ->whereNull('deleted_at')
            ->when($module, fn($q) => $q->where('module', $module))
            ->orderBy('name')
            ->get(['role_id', 'name', 'module', 'description']);

        return response()->json($rows);
    }

    public function userRoles(Request $request, int $userId)
    {
        $rows = DB::table('user_roles')
            ->join('roles', 'roles.role_id', '=', 'user_roles.role_id')
            ->join('tenants', 'tenants.tenant_id', '=', 'user_roles.tenant_id')
            ->where('user_roles.user_id', $userId)
            ->whereNull('user_roles.deleted_at')
            ->orderBy('tenants.nama')
            ->get([
                'roles.name as role',
                'user_roles.module',
                'tenants.tenant_id',
                'tenants.nama as tenant',
                'user_roles.granted_at',
                'user_roles.expires_at',
            ]);

        return response()->json($rows);
    }

    public function assignRole(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'role' => 'required|string',
            'tenant_id' => 'required|integer|exists:tenants,tenant_id',
            'module' => 'required|string',
        ]);

        try {
            $roleId = DB::table('roles')
                ->where(['name' => $data['role'], 'module' => $data['module']])
                ->value('role_id');

            if (!$roleId) {
                throw ValidationException::withMessages([
                    'role' => ['Role not found for module'],
                ])->status(404);
            }

            DB::table('user_roles')->updateOrInsert(
                [
                    'user_id' => $data['user_id'],
                    'role_id' => $roleId,
                    'tenant_id' => $data['tenant_id'],
                    'module' => $data['module'],
                ],
                [
                    'deleted_at' => null,
                    'updated_at' => now(),
                    'created_at' => now(),
                    'granted_at' => now(),
                ]
            );

            DB::table('iam_audit_logs')->insert([
                'actor_id' => $request->user()->id,
                'action' => 'assign_role',
                'payload' => json_encode($data),
                'ip' => $request->ip(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['message' => 'role assigned']);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'internal error',
                'detail' => $e->getMessage(),
            ], 500);
        }
    }

    public function revokeRole(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'role' => 'required|string',
            'tenant_id' => 'required|integer|exists:tenants,tenant_id',
            'module' => 'required|string',
        ]);

        $roleId = DB::table('roles')
            ->where(['name' => $data['role'], 'module' => $data['module']])
            ->value('role_id');

        if (!$roleId) {
            return response()->json(['message' => 'role not found for module'], 404);
        }

        $updated = DB::table('user_roles')
            ->where([
                'user_id' => $data['user_id'],
                'role_id' => $roleId,
                'tenant_id' => $data['tenant_id'],
                'module' => $data['module'],
            ])
            ->update(['deleted_at' => now(), 'updated_at' => now()]);

        DB::table('iam_audit_logs')->insert([
            'actor_id' => $request->user()->id,
            'action' => 'revoke_role',
            'payload' => json_encode($data + ['matched_rows' => $updated]),
            'ip' => $request->ip(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'role revoked (soft)']);
    }

    public function auditLogs()
    {
        $rows = DB::table('iam_audit_logs')
            ->orderByDesc('id')
            ->limit(200)
            ->get();

        return response()->json($rows);
    }
}

