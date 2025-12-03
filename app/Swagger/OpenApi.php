<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="SSO Backend API V2",
 *     version="2.0.0",
 *     description="SSO Backend API Documentation - V2 Only"
 * )
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="SSO Backend Server"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="BearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="Sanctum",
 *     description="Bearer token from login endpoint"
 * )
 * @OA\Tag(name="Auth V2", description="Authentication V2 - User authentication, registration, and profile management")
 * @OA\Tag(name="Master Data V2", description="Master Data V2 - Unit Kerja, Dinas, and Roles")
 */
final class OpenApi{}