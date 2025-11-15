<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

final class AppPaths
{
    /**
     * @OA\Get(
     *     path="/api/v1/apps",
     *     operationId="SsoMitraAppPicker",
     *     tags={"App Picker"},
     *     summary="Daftar aplikasi yang boleh diakses oleh user",
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
    public function list(): void
    {
    }
}


