<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="SSO Mitra • Identity Provider & OAuth2 Authorization Server",
 *     version="1.0.0",
 *     description="Layanan SSO Mitra menggunakan Laravel Sanctum untuk autentikasi dan OAuth2 Authorization Code Grant untuk SSO integration dengan aplikasi klien (SIPRIMA, dsb)."
 * )
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Production SSO Mitra Server"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="BearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="Sanctum",
 *     description="Bearer token dari endpoint login lokal (untuk protected endpoints yang memerlukan auth), atau access token dari OAuth2 token endpoint"
 * )
 * @OA\Tag(name="Auth", description="Autentikasi lokal - login, logout, profil, refresh token")
 * @OA\Tag(name="SSO", description="OAuth2 Authorization Code Grant Flow - authorize, token exchange, userinfo (untuk aplikasi klien seperti SIPRIMA)")
 * @OA\Tag(name="Tenant", description="Manajemen tenant/OPD - daftar dan switch tenant")
 * @OA\Tag(name="IAM", description="IAM/RBAC - manajemen role dan permissions")
 * @OA\Tag(name="App Picker", description="Daftar aplikasi yang bisa diakses user berdasarkan role")
 */
final class OpenApi{}