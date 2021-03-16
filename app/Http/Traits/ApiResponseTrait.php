<?php


namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    public function apiResponse($data = null, $message = null, $code = 200): JsonResponse
    {
        $key = ($code == 200 || $code == 201) ? 'message' : 'error';
        $status = $code == 200 || $code == 201 ? 'Success' : 'Failed';

        $array = [
            'status' => $status,
            $key => $message,
            'data' => $data,
        ];

        return response()->json($array, $code);
    }

}
