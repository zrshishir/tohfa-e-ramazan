<?php


namespace App\Helper;


class ResponseHelper
{
    public function noContent(): Array{
        return $this->responseProcess(0, 204, "No Content", "");
    }

    public function indexData($datas, $message): Array{
        return $this->responseProcess(0, 200, $message, $datas);
    }

    public function validatingErrors($errorMsg): Array{
        return $this->responseProcess(1, 422, $errorMsg, "");
    }

    public function invalidInput($message): Array{
        return $this->responseProcess(1, 403, $message, "");
    }

    public function savingData($datas): Array{
        return $this->responseProcess(0, 201, "Data has been saved.", $datas);
    }

    public function serverError($message): Array{
        return $this->responseProcess(1, 500, $message, "");
    }

    public function notNumeric($datas): Array{
        return $this->responseProcess(1, 403, "The id should be a number.", $datas);
    }

    public function deletingData($datas): Array{
        return $this->responseProcess(0, 200, "Data has been deleted successfully.", $datas);
    }

    public function invalidDeleteId($datas): Array{
        return $this->responseProcess(1, 400, "Please give a valid id.", $datas);
    }

    public function invalidLogin($message): Array{
        return $this->responseProcess(1, 401, $message, "");
    }

    public function loggedIn($datas): Array{
        return $this->responseProcess(0, 200, "You are logged in...", $datas);
    }

    public function logout(): Array{
        return $this->responseProcess(0, 200, "Successfully logged out", "");
    }

    public function responseProcess($errorCode, $statusCode, $msg, $data): Array{
        $responseData['error'] = $errorCode;
        $responseData['statusCode'] = $statusCode;
        $responseData['message'] = $msg;
        $responseData['data'] = $data;

        return $responseData;
    }
}