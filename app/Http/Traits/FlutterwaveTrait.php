<?php


trait FlutterwaveTrait {
    public function makeTransfer($data){
        $data = json_encode($data);
        $curl = curl_init();        

        curl_setopt_array($curl, array(
          CURLOPT_URL => env('FLW_BASE_URL')."/transfers",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS =>"{$data}",
          CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer ".env('FLW_SECRET_KEY'),
            "Content-Type: application/json"
          ),
        ));     

        $response = curl_exec($curl);       

        curl_close($curl);
        return $response;
    }

}