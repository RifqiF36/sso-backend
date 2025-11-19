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
     *         description="OK",
     *         @OA\JsonContent(example={
     *             {
     *                 "code":"asset",
     *                 "name":"Asset Management",
     *                 "url":"http://127.0.0.1:8000",
     *                 "icon":"https://example.com/icons/asset.png"
     *             }
     *         })
     *     )
     * )
     */
    public function list(): void {}
}
