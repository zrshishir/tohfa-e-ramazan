<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait HelperTrait {

    // response success
    public function successResponse($message = null, $data = [], $statusCode = 200) {

        return response()->json([
            'status' => 'Success',
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    // response error
    public function errorResponse($message = null, $data = [], $statusCode = 400) {
        return response()->json([
            'status' => 'Error',
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }


    public function notFoundResponse($message = null, $data = [], $statusCode = 404) {

        return response()->json([
            'status' => 'Not Found',
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }


    public function unauthorizedResponse($message = null, $data = [], $statusCode = 401) {

        return response()->json([
            'status' => 'Unauthorized',
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }


    public function validationErrorResponse($message = null, $data = [], $statusCode = 422) {

        return response()->json([
            'status' => 'Validation Error',
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    public function noContentResponse($message = null, $data = [], $statusCode = 204) {

        return response()->json([
            'status' => 'No Content',
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    public function jsonResponse($status = 'Success', $statusCode = 200, $message = null, $data = []) {

        return response()->json([$status, $statusCode, $message, $data]);
    }

}
