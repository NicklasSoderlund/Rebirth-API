<?php

class ApiView {

    public static function outputAll(array $data) 
     {
        $json = [
            'count' => count($data),
            'result' => $data
        ];
        header("Content-Type: application/json");
      echo json_encode($json);
    }

    public static function outputOne($data) 
    {
       $json = [
           'result' => $data
       ];
       header("Content-Type: application/json");
       echo json_encode($json);
   }

   public function outputRegister($post)
   {
    $json = [
        'successfully registered' => $post
    ];
    header("Content-Type: application/json");
    echo json_encode($json);
   }

   public function outputSeller($sellerInfo)
   {
    $json = [
        'Seller info' => $sellerInfo,
    ];
    header("Content-Type: application/json");
    echo json_encode($json);
   }

}