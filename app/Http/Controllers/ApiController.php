<?php

namespace App\Http\Controllers;

use \Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    public function sendResponse($result = [], $message = "The request was successfully processed."): JsonResponse
    {
        // response()->json() could be used directly, but, in order to keep consistency the following case was defined
        if (empty($result)) return response()->json([], 204);

        $response = [
            'success' => true,
            'message' => $message,
            'data' => $result,
        ];

        return response()->json($response, 200);
    }

    public function sendError($code = 400, $message = "", $errors = []): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ];

        return response()->json($response, $code);
    }
}
