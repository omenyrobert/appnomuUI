<?php
namespace App\Http\Traits;

use AfricasTalking\SDK\AfricasTalking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait SMSTrait{

    public function sendSMS($message,$phone,$title,$user_id=null){
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
            $result = $sms->sms()->send(['to'=>$tel,'message'=>$message,'from'=>'Appnomu']);
            if($result['status'] == 'success'){
                $this->saveSms($tel,$title,$message,$result['status'],$user_id ? $user_id:$user->user_id);
                return $result['status'];
            }
            return $result['status'];
        } catch (\Throwable $th) {
            //throw $th;
            return 'failed';
        }
        

    }

    public  function verifyPhone($phone,$code,$userid){
        $message = 'Your Appnomu Account Verification Code Is:'.$code;
        $ret = $this->sendSMS($message,$phone,'Telephone Verification',$userid);      
        return $ret;
        
    }

    public static function saveSms($to,$title,$message,$status,$userid){
        $sms = 'SMS-'.rand(1111,9999);
        $db = DB::table('smssent')->insert([
            'Sms_Id'=>$sms,
            'user_Id'=>$userid,
            'To'=>$to,
            'Title'=>$title,
            'Message'=>$message,
            'Status'=>$status,
            'created_at'=>Carbon::now()

        ]);
        return;

        
    }

    public  function verify_alliases_phone($phone,$code,$userid,$refferer,$all_name){
        
        $message = 'Hello '. $all_name . ' '.$refferer . ' Has Listed YOu as His/Her Alliance on Appnomu For a loan verification, to Approve please send him/her this verification code: '.$code;
        $ret = $this->sendSMS($message,$phone,'Alliace Telephone Verification',$userid); 
        return $ret;
        
    }

    // public static function saveSms($to,$title,$message,$status,$userid){
    //     $sms = 'SMS-'.rand(1111,9999);

    //     $db = DB::table('smssent')->insert([
    //         'Sms_Id'=>$sms,
    //         'user_Id'=>$userid,
    //         'To'=>$to,
    //         'Title'=>$title,
    //         'Message'=>$message,
    //         'Status'=>$status,
    //         'created_at'=>date('Y-m-d H:i:s', time())

    //     ]);

    //     if($db){
    //         return 1;
    //     }else{
    //         return 0;
    //     }
    // }
}