<?php
namespace App\Http\Traits;

use AfricasTalking\SDK\AfricasTalking;

trait SMSTrait{

    public function sendSMS($message,$phone){
        try {
            if(substr($phone,0,1)=='0'){
                //0754024461
                $tel = '+256'.substr($phone,1,9);
            }elseif (substr($phone,0,1)=='+') {
                # code...
                $tel = $phone;
            }
    
            $sms = new AfricasTalking(env('AFRICASTALKING_USERNAME') ,env('AFRICASTALKING_APIKEY'));
            $result = $sms->sms()->send(['to'=>$tel,'message'=>$message,'from'=>'Appnomu']);
            return $result['status'];
        } catch (\Throwable $th) {
            //throw $th;
            return 'failed';
        }
        

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