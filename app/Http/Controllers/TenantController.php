<?php

namespace App\Http\Controllers;

use App\Services\UserContextService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TenantController extends Controller
{
    public function index(Request $request)
    {
        $rows = UserContextService::tenants($request->user()->id)->all();

        return response()->json($rows);
    }

    public function switch(Request $request, int $tenantId)
    {
        $owned = DB::table('user_roles')
            ->where('user_id', $request->user()->id)
            ->where('tenant_id', $tenantId)
            ->whereNull('deleted_at')
            ->exists();

        if (!$owned) {
            return response()->json(['message' => 'forbidden tenant'], 403);
        }

        DB::table('iam_audit_logs')->insert([
            'actor_id' => $request->user()->id,
            'action' => 'tenant_switch',
            'payload' => json_encode(['tenant_id' => $tenantId]),
            'ip' => $request->ip(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'tenant switched', 'tenant_id' => $tenantId]);
    }
}

