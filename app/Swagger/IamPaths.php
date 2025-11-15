<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

final class IamPaths
{
    /**
     * @OA\Get(
     *     path="/api/v1/iam/roles",
     *     operationId="SsoMitraIamRoles",
     *     tags={"IAM"},
     *     summary="Daftar role berdasarkan modul",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="module",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="OK")
     * )
     */
    public function roles(): void
    {
    }

    /**
     * @OA\Post(
     *     path="/api/v1/iam/assign-role",
     *     operationId="SsoMitraIamAssign",
     *     tags={"IAM"},
     *     security={{"BearerAuth":{}}},
     *     summary="Assign role ke user",
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             required={"user_id","role","tenant_id","module"},
     *             @OA\Property(property="user_id", type="integer"),
     *             @OA\Property(property="role", type="string"),
     *             @OA\Property(property="tenant_id", type="integer"),
     *             @OA\Property(property="module", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Role assigned")
     * )
     */
    public function assign(): void
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/iam/users/{id}/roles",
     *     operationId="SsoMitraIamUserRoles",
     *     tags={"IAM"},
     *     security={{"BearerAuth":{}}},
     *     summary="Daftar role user per tenant & module",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="OK")
     * )
     */
    public function userRoles(): void
    {
    }

    /**
     * @OA\Post(
     *     path="/api/v1/iam/revoke-role",
     *     operationId="SsoMitraIamRevoke",
     *     tags={"IAM"},
     *     security={{"BearerAuth":{}}},
     *     summary="Revoke role dari user",
     *     @OA\Response(response=200, description="Role revoked")
     * )
     */
    public function revoke(): void
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/iam/audit-logs",
     *     operationId="SsoMitraIamAuditLogs",
     *     tags={"IAM"},
     *     security={{"BearerAuth":{}}},
     *     summary="Audit log IAM terbaru",
     *     @OA\Response(response=200, description="OK")
     * )
     */
    public function auditLogs(): void
    {
    }
}

