<?php
namespace App\Http\Traits;

use Carbon\Carbon;
use App\Models\ReloadlyToken;
use App\Http\Traits\ReloadlyAccessTrait;

trait UtilityTrait{
    use ReloadlyAccessTrait;

    public function getUtilityBillers(){
        $token = $this->getAccessToken('utility');
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => env('RELOADLY_UTILITY_BILLERS'),
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$token,
            'Accept: application/com.reloadly.utilities-v1+json',
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return  json_decode($response);
    }


    public function payBill($utility_data){
        $token = $this->getAccessToken('utility');
        $curl = curl_init();
        // $utility_data = array(
		// 	"subscriberAccountNumber"=>"04223568280",
		// 	"amount"=>10000,
		// 	"billerId"=>5,
		// 	"useLocalAmount"=> false
		// );
		// $utility_data = json_encode($utility_data);
        curl_setopt_array($curl, array(
          CURLOPT_URL => env('RELOADLY_UTILITY_PAY'),
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>$utility_data,
          CURLOPT_HTTPHEADER => array(
            'Accept: application/com.reloadly.utilities-v1+json',
            'Authorization: Bearer '.$token,
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return  $response;
    }

    public function getUtilityTransaction($id){
		$token = $this->getAccessToken('utility');
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => env('RELOADLY_UTITLITY_TRANSACTION')."/$id",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_HTTPHEADER => array(
		    'Accept: application/com.reloadly.utilities-v1+json',
		    "Authorization: Bearer $token"
		  ),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
	}

}