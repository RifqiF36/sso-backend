<?php

use App\Http\Controllers\AppSelectorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IamController;
use App\Http\Controllers\StaffUserController;
use App\Http\Controllers\TenantController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::get('me', [AuthController::class, 'me']);
            Route::get('roles', [AuthController::class, 'roles']);
            Route::post('refresh', [AuthController::class, 'refresh']);
            Route::put('profile', [AuthController::class, 'updateProfile']);
            Route::post('change-password', [AuthController::class, 'changePassword']);
        });

        // SSO Internal (dari IdP eksternal - jika ada)
        Route::get('sso/redirect', [AuthController::class, 'ssoRedirect']);
        Route::get('sso/callback', [AuthController::class, 'ssoCallback']);

        // SSO Endpoints - SSO Mitra sebagai IdP untuk aplikasi lain (SIPRIMA, dll)
        Route::get('sso/authorize', [AuthController::class, 'ssoAuthorize']);
        Route::post('sso/token', [AuthController::class, 'ssoToken']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('sso/userinfo', [AuthController::class, 'ssoUserinfo']);
        });
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('tenants', [TenantController::class, 'index']);
        Route::post('tenants/{tenant}/switch', [TenantController::class, 'switch']);

        Route::prefix('iam')->group(function () {
            Route::get('roles', [IamController::class, 'roles']);
            Route::get('users/{id}/roles', [IamController::class, 'userRoles']);
            Route::post('assign-role', [IamController::class, 'assignRole']);
            Route::post('revoke-role', [IamController::class, 'revokeRole']);
            Route::get('audit-logs', [IamController::class, 'auditLogs']);
        });

        Route::get('apps', [AppSelectorController::class, 'index']);
        Route::post('staff/users', [StaffUserController::class, 'store']);
    });
});
