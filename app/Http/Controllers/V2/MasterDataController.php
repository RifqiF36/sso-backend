<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Models\Unitkerja;
use App\Models\Dinas;
use App\Models\Role;

/**
 * @OA\Tag(
 * name="Master Data V2",
 * description="Master Data API - Get all unit kerja, dinas, and roles"
 * )
 */
class MasterDataController extends Controller
{
    /**
     * Get all unit kerja
     *
     * @OA\Get(
     * path="/api/v2/master/unit-kerja",
     * operationId="MasterDataV2GetAllUnitKerja",
     * tags={"Master Data V2"},
     * summary="Get all unit kerja",
     * @OA\Response(
     * response=200,
     * description="Success",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", type="array", @OA\Items(type="object"))
     * )
     * )
     * )
     */
    public function getAllUnitKerja()
    {
        $unitKerjas = Unitkerja::all();

        return response()->json([
            'success' => true,
            'data' => $unitKerjas
        ]);
    }

    /**
     * Get all dinas
     *
     * @OA\Get(
     * path="/api/v2/master/dinas",
     * operationId="MasterDataV2GetAllDinas",
     * tags={"Master Data V2"},
     * summary="Get all dinas",
     * @OA\Response(
     * response=200,
     * description="Success",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", type="array", @OA\Items(type="object"))
     * )
     * )
     * )
     */
    public function getAllDinas()
    {
        $dinas = Dinas::all();

        return response()->json([
            'success' => true,
            'data' => $dinas
        ]);
    }

    /**
     * Get all roles
     *
     * @OA\Get(
     * path="/api/v2/master/roles",
     * operationId="MasterDataV2GetAllRoles",
     * tags={"Master Data V2"},
     * summary="Get all roles",
     * @OA\Response(
     * response=200,
     * description="Success",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", type="array", @OA\Items(type="object"))
     * )
     * )
     * )
     */
    public function getAllRoles()
    {
        $roles = Role::all();

        return response()->json([
            'success' => true,
            'data' => $roles
        ]);
    }
}
