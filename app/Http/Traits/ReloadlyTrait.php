<?php
namespace App\Http\Traits;

use App\Models\ReloadlyToken;
use Carbon\Carbon;

trait ReloadlyTrait{
    public function getAccessToken($service){

		$token =  ReloadlyToken::where('type',$service)
            		->where('status','Active')->latest()->first();
		if($token){
			$expired = Carbon::now()->diffInSeconds($token->created_at) > $token->life ? true : false;
			if(!$expired){
				return $token->token;
			}
			$token->status = 'InActive';
			$token->save();
		}
        $client_id = env('RELOADLY_ID');
        $client_secret = env('RELOADLY_SECRET');
        $topup_url =  env('RELOADLY_TOPUP_URL');
        $tility_url =  env('RELOADLY_UTILITY_URL');
        $auth_url =  env('RELOADLY_AUTH_URL');
        
        switch ($service) {
            case 'topup':
               $url =env('RELOADLY_TOPUP_URL');
               
                break;
            case 'utility':
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
        
        $res_object = $response ? ['success',$response] : ['error',$err];
        if($res_object[0] == 'success'){
			$response =  json_decode($response); 
			$new_token = new ReloadlyToken();
			$new_token->status = 'Active';
			$new_token->token = $response->access_token;
			$new_token->life = $response-> expires_in;
			$new_token->type = $service;
			$new_token->save();
			return $response->access_token;
        }
		
        return 'error';
    }


    public function checkTopupBalance(){
		$token = $this->getAccessToken('topup');
        $curl = curl_init();        

        curl_setopt_array($curl, array(
          CURLOPT_URL => env('RELOADLY_TOPUP_BALANCE'),
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Accept: application/com.reloadly.topups-v1+json',
            'Authorization: '.$token,
            'Content-Type: application/json'
          ),
        ));     

        $response = curl_exec($curl);       

        curl_close($curl);
        return $response;
    }

    public function getTopupCountries(){
		$token = $this->getAccessToken('topup');
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => env('RELOADLY_TOPUP_COUNTRIES'),
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Authorization: '.$token,
            'Accept: application/com.reloadly.topups-v1+json',
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }


    public function getTopupOperators(){ 
		$token = $this->getAccessToken('topup');
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => env('RELOADLY_TOPUP_OPERATORS'),
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Authorization: '.$token,
            'Accept: application/com.reloadly.topups-v1+json',
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function getTopupOperator($id){
		$token = $this->getAccessToken('topup');
        $curl = curl_init();        

        curl_setopt_array($curl, array(
        CURLOPT_URL => env('RELOADLY_TOPUP_OPERATORS').'/'.$id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: '.$token,
            'Accept: application/com.reloadly.topups-v1+json'
        ),
        ));     

        $response = curl_exec($curl);       

        curl_close($curl);
        return $response;
    }

    public function autoDetectTopupOperator($phone,$iso){
		$token = $this->getAccessToken('topup');
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://topups.reloadly.com/operators/auto-detect/phone/'.$phone.'/countries/'.$iso.'?includeBundles=true&includeData=true&includePin=true&suggestedAmounts=true&suggestedAmountsMap=true',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Authorization: '.$token,
            'Accept: application/com.reloadly.topups-v1+json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function getTopupOperatorByIso($iso){
		$token = $this->getAccessToken('topup');
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://topups.reloadly.com/operators/countries/'.$iso.'?includeBundles=true&includeData=true&includePin=true&suggestedAmounts=true&suggestedAmountsMap=true',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Authorization: '.$token,
            'Accept: application/com.reloadly.topups-v1+json',
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }

    public function TopupforeignExchangeRate($id,$amount){
		$token = $this->getAccessToken('topup');
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => env('RELOADLY_TOPUP_FX_RATE'),
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>"{
        	'operatorId':{$id},
        	'amount':{$amount}
        }",
          CURLOPT_HTTPHEADER => array(
            'Authorization: '.$token,
            'Accept: application/com.reloadly.topups-v1+json',
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function getTopupOperatorCommissions($id){
		$token = $this->getAccessToken('topup');
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => env('RELOADLY_TOPUP_OPERATORS').'/'.$id.'/commissions',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Authorization: '.$token,
            'Accept: application/com.reloadly.topups-v1+json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function getTopPromotions(){
		$token = $this->getAccessToken('topup');
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => env('RELOADLY_TOPUP_PROMOTIONS'),
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Authorization: '.$token,
            'Accept: application/com.reloadly.topups-v1+json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function getTopupPromotion($id){
		$token = $this->getAccessToken('topup');

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => env('RELOADLY_TOPUP_PROMOTIONS').'/'.$id,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Authorization: '.$token,
            'Accept: application/com.reloadly.topups-v1+json',
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function getTopupOperatorPromotions($id){
		$token = $this->getAccessToken('topup');

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => env('RELOADLY_TOPUP_OPERATOR_PROMOTIONS ').'/'.$id,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Authorization: '.$token,
            'Accept: application/com.reloadly.topups-v1+json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function makeTopUp($topup_data){
		$token = $this->getAccessToken('topup');
        $curl = curl_init();        

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://topups.reloadly.com/topups',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>$topup_data,
          CURLOPT_HTTPHEADER => array(
            'Authorization: '.$token,
            'Accept: application/com.reloadly.topups-v1+json',
            'Content-Type: application/json'
          ),
        ));     

        $response = curl_exec($curl);       

        curl_close($curl);
        return $response;
    }
   
    
  
}