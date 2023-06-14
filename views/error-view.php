<?php

class ErrorView {

    public static function notFound() {
        http_response_code(404);
         
        echo json_encode(array("message" => "Not Found."));
    }

    public static function notAllowed() {
        http_response_code(405);
       echo json_encode(array("message" => "Input not allowed."));
    }
}