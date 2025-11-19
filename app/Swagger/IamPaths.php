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
     *         @OA\Schema(type="string", example="asset_risk")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(example={
     *             {
     *                 "role_id":1,
     *                 "name":"admin_kota",
     *                 "module":"asset_risk",
     *                 "description":"Administrator Kota"
     *             }
     *         })
     *     )
     * )
     */
    public function roles(): void {}

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
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="role", type="string", example="admin_kota"),
     *             @OA\Property(property="tenant_id", type="integer", example=1),
     *             @OA\Property(property="module", type="string", example="asset_risk"),
     *             example={"user_id":1,"role":"admin_kota","tenant_id":1,"module":"asset_risk"}
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role assigned",
     *         @OA\JsonContent(example={"message":"role assigned"})
     *     )
     * )
     */
    public function assign(): void {}

    /**
     * @OA\Get(
     *     path="/api/v1/iam/users/{id}/roles",
     *     operationId="SsoMitraIamUserRoles",
     *     tags={"IAM"},
     *     security={{"BearerAuth":{}}},
     *     summary="Daftar role user per tenant & module",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer", example=1)),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(example={
     *             {
     *                 "role":"admin_kota",
     *                 "module":"asset_risk",
     *                 "tenant_id":1,
     *                 "granted_at":"2025-11-15 00:00:00"
     *             }
     *         })
     *     )
     * )
     */
    public function userRoles(): void {}

    /**
     * @OA\Post(
     *     path="/api/v1/iam/revoke-role",
     *     operationId="SsoMitraIamRevoke",
     *     tags={"IAM"},
     *     security={{"BearerAuth":{}}},
     *     summary="Revoke role dari user",
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             required={"user_id","role","tenant_id","module"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="role", type="string", example="admin_kota"),
     *             @OA\Property(property="tenant_id", type="integer", example=1),
     *             @OA\Property(property="module", type="string", example="asset_risk"),
     *             example={"user_id":1,"role":"admin_kota","tenant_id":1,"module":"asset_risk"}
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role revoked",
     *         @OA\JsonContent(example={"message":"role revoked"})
     *     )
     * )
     */
    public function revoke(): void {}

    /**
     * @OA\Get(
     *     path="/api/v1/iam/audit-logs",
     *     operationId="SsoMitraIamAuditLogs",
     *     tags={"IAM"},
     *     security={{"BearerAuth":{}}},
     *     summary="Audit log IAM terbaru",
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(example={
     *             {
     *                 "action":"assign-role",
     *                 "user_id":1,
     *                 "role":"admin_kota",
     *                 "tenant_id":1,
     *                 "performed_by":1,
     *                 "created_at":"2025-11-15 00:00:00"
     *             }
     *         })
     *     )
     * )
     */
    public function auditLogs(): void {}
}
