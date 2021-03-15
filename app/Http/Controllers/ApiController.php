<?php

namespace App\Http\Controllers;

use \Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    public function sendResponse($result, $message = "The request was successfully processed."): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $result,
        ];

        return response()->json($response, 200);
    }

    public function sendError($message, $errors = [], $code = 404): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ];

        return response()->json($response, $code);
    }
}
