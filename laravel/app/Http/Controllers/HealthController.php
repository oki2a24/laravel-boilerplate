<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class HealthController extends Controller
{
    /**
     * 自身の生存状況を返します。
     *
     * @return JsonResponse
     */
    public function health(): JsonResponse
    {
        return response()->json([
            'status' => 'pass',
        ]);
    }
}
