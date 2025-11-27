<?php

namespace App\Swagger\V2;

use OpenApi\Annotations as OA;

final class AuthPaths
{
    /**
     * @OA\Post(
     *     path="/api/v2/auth/login",
     *     operationId="v2Login",
     *     tags={"Auth V2"},
     *     summary="Login user and get authentication token",
     *     description="Authenticate user with email and password, returns Sanctum bearer token",
     *     @OA\RequestBody(
     *         required=true,
     *         description="User credentials",
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 format="email",
     *                 description="User's email address",
     *                 example="admin_kota@sso"
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 format="password",
     *                 description="User's password",
     *                 example="AdminKota@123"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="token",
     *                 type="string",
     *                 description="Sanctum authentication token",
     *                 example="1|randomtokenstringhere"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Invalid credentials"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="The email field is required."
     *             )
     *         )
     *     )
     * )
     */
    public function login(): void {}

    /**
     * @OA\Get(
     *     path="/api/v2/auth/me",
     *     operationId="v2GetCurrentUser",
     *     tags={"Auth V2"},
     *     summary="Get current authenticated user",
     *     description="Returns the currently authenticated user's information",
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="User information retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Admin Kota"),
     *             @OA\Property(property="email", type="string", example="admin_kota@sso"),
     *             @OA\Property(
     *                 property="email_verified_at",
     *                 type="string",
     *                 format="date-time",
     *                 nullable=true,
     *                 example=null
     *             ),
     *             @OA\Property(
     *                 property="created_at",
     *                 type="string",
     *                 format="date-time",
     *                 example="2025-01-01T00:00:00.000000Z"
     *             ),
     *             @OA\Property(
     *                 property="updated_at",
     *                 type="string",
     *                 format="date-time",
     *                 example="2025-01-01T00:00:00.000000Z"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Unauthenticated."
     *             )
     *         )
     *     )
     * )
     */
    public function me(): void {}

    /**
     * @OA\Post(
     *     path="/api/v2/auth/logout",
     *     operationId="v2Logout",
     *     tags={"Auth V2"},
     *     summary="Logout user and revoke all tokens",
     *     description="Revokes all authentication tokens for the current user",
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout successful",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Logged out successfully"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Unauthenticated."
     *             )
     *         )
     *     )
     * )
     */
    public function logout(): void {}
}
