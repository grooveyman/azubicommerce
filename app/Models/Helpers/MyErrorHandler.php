<?php

namespace App\Models\Helpers;

class MyErrorHandler{

    
    public static function errorMsg($errorcode){
        switch($errorcode){
            case "500":
                return response()->json(["status"=>$errorcode, "message"=>"Server responded with an error"], $errorcode);
            case "422":
                return response()->json(["status"=>$errorcode, "message"=>"Server responded with a Validation error"], $errorcode);
            case "404":
                return response()->json(["status"=>$errorcode, "message"=>"Server responded with a Not Found error"], $errorcode);
        
        }
    }
}