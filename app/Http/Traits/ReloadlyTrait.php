<?php
namespace App\Http\Traits;


trait ReloadlyTrait{
    public function getAccessToken($service){
        $client_id = env('RELOADLY_ID');
        $client_secret = env('RELOADLY_SECRET');
        $topup_url =  env('RELOADLY_TOPUP_URL');
        $tility_url =  env('RELOADLY_UTILITY_URL');
        $auth_url =  env('RELOADLY_AUTH_URL');
        
        switch ($service) {
            case 'topup':
               $url =env('RELOADLY_TOPUP_URL');
                break;
            case 'utilities':
                $url =env('RELOADLY_UTILITY_URL');
                 break;           
            
        }

        $credentials = [
            "client_id"=>$client_id,
	        "client_secret"=>$client_secret,
	        "grant_type"=>"client_credentials",
	        "audience"=>$url
        ];
        $curl = curl_init();
    
        curl_setopt_array($curl, array(
            CURLOPT_URL => $auth_url,// your preferred url
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($credentials),
            CURLOPT_HTTPHEADER => array(
                // Set here requred headers
                // "accept: */*",
                // "accept-language: en-US,en;q=0.8",
                "content-type: application/json",
            ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        $object = $response ? ['success',$response] : ['error',$err];
        return $object; 
    }


    public function checkBalance(){
        
    }


   
    
  
}