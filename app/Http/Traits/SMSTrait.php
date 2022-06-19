<?php
namespace App\Http\Traits;

use AfricasTalking\SDK\AfricasTalking;
use App\Models\SMS;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait SMSTrait{

    public function sendSMS($message,$phone,$title,$user){
        try {
            if(substr($phone,0,1)=='0'){
                $user = User::where('telephone',$phone)->first();
                $country = $user->country;
                $tel = $country->country_code.substr($phone,1,9);
            }elseif (substr($phone,0,1)=='+') {
                # code...
                $tel = $phone;
            }
    
            $sms = new AfricasTalking(env('AFRICASTALKING_USERNAME') ,env('AFRICASTALKING_APIKEY'));
            $result = $sms->sms()->send(['to'=>$phone,'message'=>$message]);
            // dd($result);
            $sms_data = json_decode(json_encode($result['data'],true));
            $sms_status = get_object_vars(get_object_vars(get_object_vars($sms_data)['SMSMessageData'])['Recipients'][0])['status'];
            if($sms_status == 'Success'){
                $this->saveSms($phone,$title,$message,$result['status'],$user);
            }
            return $sms_status;
        } catch (\Throwable $th) {
            throw $th;
            // return 'failed';
        }
        

    }

    public  function verifyPhone($phone,$code,$user){
        $message = "Dear $user->name ,Your Appnomu Account Verification Code Is: $code";
        $ret = $this->sendSMS($message,$phone,'Telephone Verification',$user);      
        return $ret;
        
    }

    public  function saveSms($to,$title,$message,$status,$user){
        $sms = new SMS();
        $sms->Title = $title;
        $sms->Message = $message;
        $sms->To = $to;
        $sms->Status = $status;
        $sms->user()->associate($user);
        $sms->save();
        return;        
    }

    public  function verifyAlliance($phone,$code,$user,$all_name){
        
        $message = 'Hello '. $all_name . ' '.$user->name . ' Has Listed YOu as His/Her Alliance on Appnomu For a loan verification, to Approve please send him/her this verification code: '.$code;
        $ret = $this->sendSMS($message,$phone,'Alliace Telephone Verification',$user); 
        return $ret;
        
    }

    
}