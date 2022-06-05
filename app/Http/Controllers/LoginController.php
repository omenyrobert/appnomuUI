<?php

namespace App\Http\Controllers;

use App\Http\Traits\SMSTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmMail;
use Carbon\Carbon;

class LoginController extends Controller
{
    use SMSTrait;
    //authenticate the user  
    public function authenticate(Request $request){
        $credentials = $request->only('email','password');

        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            $user = User::find(Auth::id());
            if(!$user->email_verified_at){
                
                Mail::to($request['email'])->send(new ConfirmMail($request->email,$user->verify_token ));
                return redirect()->route('verify')->with(['email'=>$request->email]);
            }
            return redirect()->intended('/dashboard');
        }
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
            'password'
        ]);
    }

    public function register(Request $request){
        $validated = $request->validate([
            'name'=>'required',
            'email'=>'required',
            'telephone'=>'required',
            'country'=>'required',
            'password'=>'required|min:8'
        ]);
        if($validated){
            $user = User::where('email',$request->email)->orWhere('telephone',$request->telephone)->first();
            if(!$user){
                $user_id = rand(100000,999999);
                $refferer = User::where('user_id',$request->refferer)->first();
                if($refferer) {
                    AuthenticationController::creditRefferer($refferer->user_id,$user_id,500);
                }
                $verify_token = rand(111111,999999);
                $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->telephone = $request->telephone;
                $user->password = bcrypt($request->password);
                $user->user_id = $user_id;
                $user->verify_token = $verify_token;
                $user->save();
                if($user){
                    Mail::to($request->email)->send(new ConfirmMail($request->email,$verify_token));
                    return redirect()->route('verify')->with(['email'=>$request->email]);
                }
             
            } else {
                return redirect()->back()->withErrors(['Errors'=>'Email or Phone Number Already Used, Please Login instead']);
            
            }
        }
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login');
    }

    public static function verifyUserEmail($email,$code){
        try {
            $user = User::where('email',$email)->first();
            if ($user) {
                if ($user->verify_token == $code) {
                    $sms_token = rand(111111,999999);
                    $user->email_verified_at = Carbon::now();
                    $user->sms_token = $sms_token;
                    $user->save();                   
                    $this->verifyPhone($user->telephone,$sms_token,$user->user_id);
                    return;           
                   
                } 
                return redirect()->back()->withErrors('Error','unknown code entered');
            }
            return redirect()->route('login')->withErrors('Error','unauthorised');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

   
   
}
