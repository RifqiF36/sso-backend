<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

final class TenantPaths
{
    /**
     * @OA\Get(
     *     path="/api/v1/tenants",
     *     operationId="SsoMitraTenantList",
     *     tags={"Tenant"},
     *     summary="Daftar tenant milik user",
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
    public function listTenants(): void
    {
    }

    /**
     * @OA\Post(
     *     path="/api/v1/tenants/{tenant}/switch",
     *     operationId="SsoMitraTenantSwitch",
     *     tags={"Tenant"},
     *     summary="Switch tenant aktif",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="tenant",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tenant switched"
     *     )
     * )
     */
    public function switchTenant(): void
    {
    }
}

