<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SwaggerTestController extends Controller
{
    /**
     * 測試 Swagger 的 POST API
     * 
     * @OA\Post(
     *     path="/api/test-post",
     *     summary="測試 POST",
     *     tags={"Swagger"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email"},
     *             @OA\Property(property="name", type="string", example="Joey"),
     *             @OA\Property(property="email", type="string", example="joey@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功回應",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="收到資料！"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function testPost(Request $request)
    {
        return response()->json([
            'message' => '收到資料！',
            'data' => $request->all(),
        ]);
    }
}
