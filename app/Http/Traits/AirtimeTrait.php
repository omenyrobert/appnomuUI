<?php
namespace App\Http\Traits;

use Carbon\Carbon;
use App\Models\ReloadlyToken;
use App\Http\Traits\ReloadlyAccessTrait;

trait AirtimeTrait{
    use ReloadlyAccessTrait;

    
    public function getTopupCountries(){
		$token = $this->getAccessToken('topup');
        $curl = curl_init();
		$setup_params = array(
			CURLOPT_URL => env('RELOADLY_TOPUP_COUNTRIES'),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
			'Authorization: Bearer '.$token,
			  'Accept'=> 'application/com.reloadly.topups-v1+json',
			),
		);
		// dd($setup_params);
        curl_setopt_array($curl,$setup_params );

        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response);
        return $response;
    }

    public function getCountryCodes(){
      // $token = $this->getAccessToken('topup');
          $curl = curl_init();
      $setup_params = array(
        CURLOPT_URL => 'http://country.io/phone.json',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        // CURLOPT_HTTPHEADER => array(
        // 'Authorization: Bearer '.$token,
        //   'Accept'=> 'application/com.reloadly.topups-v1+json',
        // ),
      );
      // dd($setup_params);
          curl_setopt_array($curl,$setup_params );
  
          $response = curl_exec($curl);
          curl_close($curl);
          $response= json_decode($response,true);
          return $response;
      }


    public function getTopupOperators(){ 
		$token = $this->getAccessToken('topup');
        $curl = curl_init();
		$setup_params =  array(
			CURLOPT_URL => env('RELOADLY_TOPUP_OPERATORS'),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer '.$token,
			  'Accept:application/com.reloadly.topups-v1+json',
			),
		);
		// dd($setup_params);
        curl_setopt_array($curl,$setup_params);

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);
        // dd($response->content);
        return $response->content;
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
            'Authorization: Bearer '.$token,
            'Accept: application/com.reloadly.topups-v1+json'
        ),
        ));     

        $response = curl_exec($curl);       

        curl_close($curl);
        return json_decode($response);
    }

    public function autoDetectTopupOperator($phone,$iso){
		$token = $this->getAccessToken('topup');
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => env('RELOADLY_TOPUP_AUTODETECT_OPERATOR').$phone.'/countries/'.$iso.'?includeBundles=true&includeData=true&includePin=true&suggestedAmounts=true&suggestedAmountsMap=true',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$token,
            'Accept: application/com.reloadly.topups-v1+json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
    }

    public function getTopupOperatorByIso($iso){
		$token = $this->getAccessToken('topup');
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => env('RELOADLY_TOPUP_OPERATORS').'/countries/'.$iso,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$token,
            'Accept: application/com.reloadly.topups-v1+json',
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }

    public function topupforeignExchangeRate($id,$amount){
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
			'Authorization: Bearer '.$token,
            'Accept: application/com.reloadly.topups-v1+json',
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
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
            'Authorization: Bearer '.$token,
            'Accept: application/com.reloadly.topups-v1+json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
		return json_decode($response);
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
			'Authorization: Bearer '.$token,
            'Accept: application/com.reloadly.topups-v1+json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
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
            'Authorization: Bearer '.$token,
            'Accept: application/com.reloadly.topups-v1+json',
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
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
            'Authorization: Bearer '.$token,
            'Accept: application/com.reloadly.topups-v1+json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
		return json_decode($response);
    }

    public function makeTopUp($topup_data){
		$token = $this->getAccessToken('topup');
		$topup_data = json_encode($topup_data);		
    // dd($topup_data);
        $curl = curl_init();        
        curl_setopt_array($curl, array(
          CURLOPT_URL => env('RELOADLY_TOPUP'),
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>"{$topup_data}",
          CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$token,
            'Accept: application/com.reloadly.topups-v1+json',
            'Content-Type: application/json'
          ),
        ));     

        $response = curl_exec($curl);       

        curl_close($curl);
        return json_decode($response);
    }
   
    
  
}