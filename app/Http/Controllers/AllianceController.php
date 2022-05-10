<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alliance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AllianceController extends Controller
{
    

    public function store(Request $request){
        try {
            $validated = $request->validate([
                'relationship'=>'required',
                'nin'=>'required',
                'card_no'=>'required',
                'telephone'=>'required',
                'name'=>'required'
            ]);
    
            if($validated){
                $user = User::find(Auth::id());
    
                if($user->telephone == $request->telephone){
                    return redirect()->back()->withErrors(['Errors'=>'You can not use your own phone number']);
                }
                if($user->NIN == $request->nin){
                    return redirect()->back()->withErrors(['Errors'=>'You can not use your own NIN']);
                }
                $user_id = rand(111111,999999);
                $sms_code = rand(111111,999999);
                $alliance = new Alliance();
                $alliance->user()->associate($user);
                $alliance->refferer = $user->user_id;
                $alliance->relationship = $request->relationship;
                $alliance->NIN =  $request->nin;
                $alliance->Card_No =$request->card_no;
                $alliance->name = $request->name;
                $alliance->sms_token = $sms_code;
                $alliance->Phone_Number = $request->telephone;
                $alliance->Uuser_id = $user_id;
                $alliance->save();
                if ($alliance) {
                    # code...
                    $ret = SmsController::verify_alliases_phone($alliance->Phone_Number,$sms_code,session('user_id'),$user->name,$alliance->name);
                    return redirect()->back()->with('Success','Alliance Saved Successfully');
                }else {
                    # code...
                    return redirect()->back()->withErrors(['Errors'=>'Alliance Saving failed']);
                }
            }
            return redirect()->back()->withErrors(['Errors'=>'Enter all the required fields correctly']);
        } catch (\Throwable $th) {
            throw $th;
        }       
           
        
    }

    public function confirmAlliance(Request $request){
        try {
            $user = User::find(Auth::id());
            $alliance = $user->alliances()->where('sms_token',$request->token)->first();
            if($alliance)  {
               $alliance->sms_verified_at = Carbon::now();
               return redirect()->back()->with('Success','Alliance Successfully Confirmed');
            }
            return redirect()->back()->withErrors(['Errors'=>'Unknown alliance or Wrong Token']);
        } catch (\Throwable $th) {
            throw $th;
            // return redirect()->back()->withErrors(['Error'=>'Alliance Not Successfully Confirmed']);
        }
       
    }

    public function index(){
        try {
            $user = User::find(Auth::id());
            $alliances = $user->alliances;           
            return view('alliances.index',['user'=>$user,'alliances'=>$alliances])->with('page','alliances');
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }
    
   
}
