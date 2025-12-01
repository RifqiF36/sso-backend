<?php

use App\Http\Controllers\AppSelectorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IamController;
use App\Http\Controllers\StaffUserController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\V2\AuthController as V2AuthController;
use App\Http\Controllers\V2\MasterDataController as V2MasterDataController;
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
        Route::get('sso/userinfo', [AuthController::class, 'ssoUserinfo']);
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

// API V2 Routes
Route::prefix('v2')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('register', [V2AuthController::class, 'register']);
        Route::post('login', [V2AuthController::class, 'login']);
        Route::post('verify', [V2AuthController::class, 'verify']);
        Route::post('reset-password', [V2AuthController::class, 'resetPassword']);
        Route::post('confirm-reset-password', [V2AuthController::class, 'confirmResetPassword']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('me', [V2AuthController::class, 'me']);
            Route::get('users', [V2AuthController::class, 'getAllUsers']);
            Route::get('user/{id}', [V2AuthController::class, 'byId']);
            Route::put('user/{id}', [V2AuthController::class, 'updateUser']);
            Route::delete('user/{id}', [V2AuthController::class, 'deleteUser']);
            Route::post('logout', [V2AuthController::class, 'logout']);
        });
    });

    // Master Data Routes
    Route::prefix('master')->group(function () {
        Route::get('unit-kerja', [V2MasterDataController::class, 'getAllUnitKerja']);
        Route::get('dinas', [V2MasterDataController::class, 'getAllDinas']);
        Route::get('roles', [V2MasterDataController::class, 'getAllRoles']);
    });
});
