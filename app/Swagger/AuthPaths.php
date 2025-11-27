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
     *             @OA\Property(property="email", type="string", example="admin_kota@sso"),
     *             @OA\Property(property="password", type="string", example="AdminKota@123"),
     *             example={"email":"admin_kota@sso","password":"AdminKota@123"}
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil login",
     *         @OA\JsonContent(
     *             example={
     *                 "token":"1|admin-kota-demo-token",
     *                 "user":{
     *                     "id":1,
     *                     "name":"Admin Kota",
     *                     "email":"admin_kota@sso",
     *                     "roles":{{
     *                         "role":"admin_kota",
     *                         "module":"asset_risk",
     *                         "tenant_id":1,
     *                         "granted_at":"2025-11-15 00:00:00",
     *                         "expires_at":null
     *                     }},
     *                     "tenants":{{"tenant_id":1,"nama":"Diskominfo Kota","kode":"diskominfo"}},
     *                     "apps":{{"code":"asset","name":"Asset Management","url":"http://127.0.0.1:8000"}}
     *                 }
     *             },
     *             @OA\Property(property="token", type="string"),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Admin Kota"),
     *                 @OA\Property(property="email", type="string", example="admin_kota@sso"),
     *                 @OA\Property(
     *                     property="roles",
     *                     type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="role", type="string", example="admin_kota"),
     *                         @OA\Property(property="module", type="string", example="asset_risk"),
     *                         @OA\Property(property="tenant_id", type="integer", example=1),
     *                         @OA\Property(property="granted_at", type="string", example="2025-11-15 00:00:00"),
     *                         @OA\Property(property="expires_at", type="string", example=null)
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="tenants",
     *                     type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="tenant_id", type="integer", example=1),
     *                         @OA\Property(property="nama", type="string", example="Diskominfo Kota"),
     *                         @OA\Property(property="kode", type="string", example="diskominfo")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="apps",
     *                     type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="code", type="string", example="asset"),
     *                         @OA\Property(property="name", type="string", example="Asset Management"),
     *                         @OA\Property(property="url", type="string", example="http://127.0.0.1:8000")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(example={"message":"Invalid credentials."})
     *     )
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
     *     @OA\Response(
     *         response=200,
     *         description="Token revoked",
     *         @OA\JsonContent(example={"message":"logged out"})
     *     )
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
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(example={
     *             "id":1,
     *             "name":"Admin Kota",
     *             "email":"admin_kota@sso",
     *             "roles":{{"role":"admin_kota","module":"asset_risk","tenant_id":1}},
     *             "tenants":{{"tenant_id":1,"nama":"Diskominfo Kota","kode":"diskominfo"}},
     *             "apps":{{"code":"asset","name":"Asset Management","url":"http://127.0.0.1:8000"}}
     *         })
     *     )
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
     *     @OA\Parameter(name="module", in="query", required=false, @OA\Schema(type="string", example="asset_risk")),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(example={"roles":{{"role":"admin_kota","module":"asset_risk","tenant_id":1}}})
     *     )
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
     *     @OA\Response(
     *         response=200,
     *         description="Info",
     *         @OA\JsonContent(example={"message":"Use /auth/login to get a new token"})
     *     )
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
     *     @OA\Parameter(name="state", in="query", required=true, @OA\Schema(type="string", example="state-demo-123")),
     *     @OA\Parameter(name="response_type", in="query", required=false, @OA\Schema(type="string", example="code")),
     *     @OA\Response(
     *         response=200,
     *         description="Authorization code berhasil dibuat (valid 5 menit)",
     *         @OA\JsonContent(example={
     *             "data":{
     *                 "auth_code":"1e1c5e7c1c6d40b0bdb4b7f777281234",
     *                 "state":"state-demo-123",
     *                 "redirect_uri":"http://127.0.0.1:8000/api/v1/auth/sso/callback"
     *             }
     *         })
     *     ),
     *     @OA\Response(response=400, description="Invalid client_id / redirect_uri")
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
     *             required={"code","client_id","client_secret","grant_type","redirect_uri"},
     *             @OA\Property(property="code", type="string", example="1e1c5e7c1c6d40b0bdb4b7f777281234"),
     *             @OA\Property(property="client_id", type="string", example="siprima-app"),
     *             @OA\Property(property="client_secret", type="string", example="supersecret123"),
     *             @OA\Property(property="grant_type", type="string", example="authorization_code"),
     *             @OA\Property(
     *                 property="redirect_uri",
     *                 type="string",
     *                 example="http://127.0.0.1:8000/api/v1/auth/sso/callback",
     *                 description="Harus sama dengan redirect URI saat authorize"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Access token berhasil dibuat",
     *         @OA\JsonContent(example={
     *             "data":{
     *                 "access_token":"2|external-token-demo",
     *                 "token_type":"Bearer",
     *                 "expires_in":7200
     *             }
     *         })
     *     ),
     *     @OA\Response(response=400, description="Invalid client credential"),
     *     @OA\Response(response=401, description="Authorization code tidak valid / expired")
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
     *     @OA\Parameter(
     *         name="sso_token",
     *         in="query",
     *         required=false,
     *         description="Opsional: kirimkan token melalui query jika tidak bisa mengirim header Authorization (alias: token)",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User info berhasil diambil",
     *         @OA\JsonContent(example={
     *             "id":1,
     *             "username":"admin_kota@sso",
     *             "email":"admin_kota@sso",
     *             "name":"Admin Kota",
     *             "roles":{"admin_kota"}
     *         })
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
     *         @OA\JsonContent(example={"url":"https://idp.example.com/oauth/authorize?..."})
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
     *     @OA\Parameter(name="code", in="query", required=true, @OA\Schema(type="string", example="demo-code")),
     *     @OA\Parameter(name="state", in="query", required=true, @OA\Schema(type="string", example="demo-state")),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil autentikasi",
     *         @OA\JsonContent(example={
     *             "token":"3|admin-kota-sso",
     *             "user":{
     *                 "id":1,
     *                 "name":"Admin Kota",
     *                 "email":"admin_kota@sso"
     *             },
     *             "provider":"default"
     *         })
     *     ),
     *     @OA\Response(response=400, description="State/Code invalid"),
     *     @OA\Response(response=401, description="Tidak bisa tukar authorization code")
     * )
     */
    public function ssoCallback(): void {}
}
