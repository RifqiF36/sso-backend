<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

final class StaffPaths
{
    /**
     * @OA\Post(
     *     path="/api/v1/staff/users",
     *     operationId="SsoMitraStaffCreateUser",
     *     tags={"Staff"},
     *     summary="Membuat akun user baru (hanya untuk role staff)",
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","role"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", minLength=8, example="SecurePass123"),
     *             @OA\Property(property="role", type="string", maxLength=100, example="admin_opd"),
     *             @OA\Property(property="nip", type="string", nullable=true, maxLength=100, example="199001012015011001"),
     *             @OA\Property(property="gender", type="string", nullable=true, maxLength=50, example="Laki-laki"),
     *             @OA\Property(property="unit_kerja", type="string", nullable=true, maxLength=255, example="Bidang TIK"),
     *             @OA\Property(property="asal_dinas", type="string", nullable=true, maxLength=255, example="Dinas Komunikasi dan Informatika"),
     *             example={
     *                 "name":"John Doe",
     *                 "email":"john.doe@example.com",
     *                 "password":"SecurePass123",
     *                 "role":"admin_opd",
     *                 "nip":"199001012015011001",
     *                 "gender":"Laki-laki",
     *                 "unit_kerja":"Bidang TIK",
     *                 "asal_dinas":"Dinas Komunikasi dan Informatika"
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Akun berhasil dibuat",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Akun baru berhasil dibuat."),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=2),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                 @OA\Property(
     *                     property="profile",
     *                     type="object",
     *                     @OA\Property(property="nip", type="string", example="199001012015011001"),
     *                     @OA\Property(property="gender", type="string", example="Laki-laki"),
     *                     @OA\Property(property="unit_kerja", type="string", example="Bidang TIK"),
     *                     @OA\Property(property="asal_dinas", type="string", example="Dinas Komunikasi dan Informatika")
     *                 ),
     *                 @OA\Property(
     *                     property="roles",
     *                     type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="role", type="string", example="admin_opd"),
     *                         @OA\Property(property="module", type="string", example="asset_risk"),
     *                         @OA\Property(property="tenant_id", type="integer", example=1)
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Hanya staff yang dapat menambahkan akun",
     *         @OA\JsonContent(example={"message":"Hanya staff yang dapat menambahkan akun."})
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(example={
     *             "message":"The email has already been taken.",
     *             "errors":{
     *                 "email":{"The email has already been taken."}
     *             }
     *         })
     *     )
     * )
     */
    public function store(): void {}
}
