<?php
namespace App\Http\Traits;
use Illuminate\Http\Request;
trait FlutterwaveTrait {
    public function makeTransfer($data){
        $data = json_encode($data);
        $token = env('FLW_SECRET_KEY');
        $curl = curl_init();        
        $curl_data = array(
          CURLOPT_URL => env('FLW_BASE_URL')."/transfers",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS =>"$data",
          CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer $token",
            "Content-Type: application/json"
          ),
        );
		$header = array(
            "Authorization: Bearer ".env('FLW_SECRET_KEY'),
            "Content-Type: application/json"
		);
        curl_setopt_array($curl, $curl_data); 
		// curl_setopt($curl, CURLOPT_URL ,env('FLW_BASE_URL')."/transfers");
		// curl_setopt($curl, CURLOPT_RETURNTRANSFER ,true);
		// curl_setopt($curl, CURLOPT_ENCODING ,"");
		// curl_setopt($curl,  CURLOPT_MAXREDIRS ,10);
		// curl_setopt($curl,   CURLOPT_TIMEOUT ,0);
		// curl_setopt($curl,   CURLOPT_FOLLOWLOCATION ,true);
		// curl_setopt($curl, CURLOPT_HTTP_VERSION ,CURL_HTTP_VERSION_1_1);
		// curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		// curl_setopt($curl, CURLOPT_POSTFIELDS,"$data");
		// curl_setopt($curl, CURLOPT_HTTPHEADER ,$header);
		// dd($curl);
        $response = curl_exec($curl);       
        // dd($response);
        curl_close($curl);
        return $response;
    }

    public function getTransferFee($amount,$currency='UGX',$type='mobilemoney'){
      $query_params = [
        'amount'=>$amount,
        'currency' => $currency,
        'type' => $type
      ];
      $query_string = http_build_query($query_params);
	//   return $query_string;
      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => env('FLW_BASE_URL')."/transfers/fee?".$query_string,
		// CURLOPT_URL => env('FLW_BASE_URL')."/transfers/fee?amount=10000",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
          "Authorization: Bearer ".env('FLW_SECRET_KEY')
        ),
      ));
      $response = curl_exec($curl);

      curl_close($curl);
      return $response;
    }

	public function getBanks($country_iso='UG'){

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => env('FLW_BASE_URL')."/banks/$country_iso",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "Authorization: Bearer ".env('FLW_SECRET_KEY')
		  ),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
	}

	public function getBankBranches($bank){

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL =>  env('FLW_BASE_URL')."/banks/$bank/branches",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "Authorization: Bearer ".env('FLW_SECRET_KEY')
		  ),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
	}

	// public function validateWithdrawRequest(Request $request){
	// 	$account_bank = $request->account_bank;
	// 	$account_number = $request->number;
	// 	if($account_bank == 'MPS' && substr($account_number,0))

	// }

	public function collectPayment($details){
		$details = json_encode($details);
		$curl = curl_init();        
        $curl_data = array(
          CURLOPT_URL => env('FLW_BASE_URL')."/payments",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS =>$details,
          CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer ".env('FLW_SECRET_KEY'),
            "Content-Type: application/json"
          ),
        );
        curl_setopt_array($curl, $curl_data);     
        // dd($curl_data);
        $response = curl_exec($curl);       
        // dd($response);
        curl_close($curl);
        return $response; 
	}

	public function verifyTransaction($id){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL =>  env('FLW_BASE_URL')."/transactions/$id/verify",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "Authorization: Bearer ".env('FLW_SECRET_KEY')
		  ),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
	}

}