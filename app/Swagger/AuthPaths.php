<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

final class AuthPaths
{
    /**
     * @OA\Post(
     *     path="/api/v1/auth/login",
     *     operationId="SsoMitraLogin",
     *     tags={"Auth"},
     *     summary="Login user (Sanctum Token)",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", example="asset_admin@asetrisk"),
     *             @OA\Property(property="password", type="string", example="S1pr!ma123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil login",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string"),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Asset Admin"),
     *                 @OA\Property(property="email", type="string", example="asset_admin@asetrisk"),
     *                 @OA\Property(property="roles", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="role", type="string", example="asset_admin"),
     *                         @OA\Property(property="module", type="string", example="asset_risk"),
     *                         @OA\Property(property="tenant_id", type="integer", example=1)
     *                     )
     *                 ),
     *                 @OA\Property(property="tenants", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="tenant_id", type="integer", example=1),
     *                         @OA\Property(property="nama", type="string", example="Diskominfo Kota"),
     *                         @OA\Property(property="kode", type="string", example="diskominfo"))
     *                 ),
     *                 @OA\Property(property="apps", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="code", type="string", example="asset"),
     *                         @OA\Property(property="name", type="string", example="Asset Management"),
     *                         @OA\Property(property="url", type="string", example="http://127.0.0.1:8000"))
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Invalid credentials")
     * )
     */
    public function login(): void {}

    /**
     * @OA\Post(
     *     path="/api/v1/auth/logout",
     *     operationId="SsoMitraLogout",
     *     tags={"Auth"},
     *     summary="Logout dan revoke token aktif",
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(response=200, description="Token revoked")
     * )
     */
    public function logout(): void {}

    /**
     * @OA\Get(
     *     path="/api/v1/auth/me",
     *     operationId="SsoMitraMe",
     *     tags={"Auth"},
     *     summary="Profil user aktif + konteks",
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(response=200, description="OK")
     * )
     */
    public function me(): void {}

    /**
     * @OA\Get(
     *     path="/api/v1/auth/roles",
     *     operationId="SsoMitraRoles",
     *     tags={"Auth"},
     *     summary="Daftar role user",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(name="module", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="OK")
     * )
     */
    public function roles(): void {}

    /**
     * @OA\Post(
     *     path="/api/v1/auth/refresh",
     *     operationId="SsoMitraRefresh",
     *     tags={"Auth"},
     *     summary="Informasi refresh token",
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(response=200, description="Info")
     * )
     */
    public function refresh(): void {}

    /**
     * @OA\Get(
     *     path="/api/v1/auth/sso/authorize",
     *     operationId="SsoMitraSsoAuthorize",
     *     tags={"SSO"},
     *     summary="Get authorization code untuk client aplikasi (OAuth2 Authorization Code Grant)",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(name="client_id", in="query", required=true, @OA\Schema(type="string", example="siprima-app")),
     *     @OA\Parameter(name="redirect_uri", in="query", required=true, @OA\Schema(type="string", example="http://127.0.0.1:8000/api/v1/auth/sso/callback")),
     *     @OA\Parameter(name="response_type", in="query", required=true, @OA\Schema(type="string", example="code")),
     *     @OA\Parameter(name="state", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Response(
     *         response=200,
     *         description="Authorization code berhasil dibuat (valid 5 menit)",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="auth_code", type="string", example="xxxxxxxxxxxxxxxx"),
     *                 @OA\Property(property="state", type="string"),
     *                 @OA\Property(property="redirect_uri", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=400, description="Invalid client_id atau redirect_uri tidak sesuai"),
     *     @OA\Response(response=401, description="User belum login")
     * )
     */
    public function ssoAuthorize(): void {}

    /**
     * @OA\Post(
     *     path="/api/v1/auth/sso/token",
     *     operationId="SsoMitraSsoToken",
     *     tags={"SSO"},
     *     summary="Tukar authorization code dengan access token (OAuth2 Token Exchange)",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"code","client_id","client_secret","grant_type"},
     *             @OA\Property(property="code", type="string", example="xxxxxxxxxxxxxxxx"),
     *             @OA\Property(property="client_id", type="string", example="siprima-app"),
     *             @OA\Property(property="client_secret", type="string", example="supersecret123"),
     *             @OA\Property(property="grant_type", type="string", example="authorization_code")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Access token berhasil dibuat",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="access_token", type="string", example="1|xxxxxxxxxxxxxxxxxxxx"),
     *                 @OA\Property(property="token_type", type="string", example="Bearer"),
     *                 @OA\Property(property="expires_in", type="integer", example=7200)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=400, description="Invalid client_id atau client_secret"),
     *     @OA\Response(response=401, description="Authorization code tidak valid atau expired")
     * )
     */
    public function ssoToken(): void {}

    /**
     * @OA\Get(
     *     path="/api/v1/auth/sso/userinfo",
     *     operationId="SsoMitraSsoUserinfo",
     *     tags={"SSO"},
     *     summary="Dapatkan informasi user dan roles (untuk keperluan callback client)",
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="User info berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="roles", type="array",
     *                 @OA\Items(type="object",
     *                     @OA\Property(property="role", type="string"),
     *                     @OA\Property(property="module", type="string")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="User belum login")
     * )
     */
    public function ssoUserinfo(): void {}

    /**
     * @OA\Get(
     *     path="/api/v1/auth/sso/redirect",
     *     operationId="SsoMitraSsoRedirect",
     *     tags={"SSO"},
     *     summary="Dapatkan URL redirect ke IdP (legacy - untuk client lain)",
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(property="url", type="string")
     *         )
     *     )
     * )
     */
    public function ssoRedirect(): void {}

    /**
     * @OA\Get(
     *     path="/api/v1/auth/sso/callback",
     *     operationId="SsoMitraSsoCallback",
     *     tags={"SSO"},
     *     summary="Callback dari IdP (legacy - untuk client lain)",
     *     @OA\Parameter(name="code", in="query", required=true, @OA\Schema(type="string")),
     *     @OA\Parameter(name="state", in="query", required=true, @OA\Schema(type="string")),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil autentikasi",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string"),
     *             @OA\Property(property="user", type="object"),
     *             @OA\Property(property="provider", type="string")
     *         )
     *     ),
     *     @OA\Response(response=400, description="State/Code invalid"),
     *     @OA\Response(response=401, description="Tidak bisa tukar authorization code")
     * )
     */
    public function ssoCallback(): void {}
}

