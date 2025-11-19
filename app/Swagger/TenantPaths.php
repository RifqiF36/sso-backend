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
     *         description="OK",
     *         @OA\JsonContent(example={
     *             {
     *                 "tenant_id":1,
     *                 "nama":"Diskominfo Kota",
     *                 "kode":"diskominfo"
     *             }
     *         })
     *     )
     * )
     */
    public function listTenants(): void {}

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
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tenant switched",
     *         @OA\JsonContent(example={
     *             "active_tenant":{
     *                 "tenant_id":1,
     *                 "nama":"Diskominfo Kota",
     *                 "kode":"diskominfo"
     *             }
     *         })
     *     )
     * )
     */
    public function switchTenant(): void {}
}
