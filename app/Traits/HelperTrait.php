<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait HelperTrait {
    // Add your helper methods here
    public function formatName($name) {
        return ucwords(strtolower($name));
    }

    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString;
    }

    public function jsonResponse($status = 'Success', $statusCode = 200, $message = null, $data = []) {
        return response()->json([$status, $statusCode, $message, $data]);
    }
    // Add more helper methods as needed
}
