<?php

namespace App\Http\Controllers;

use App\Http\Traits\SMSTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Symfony\Contracts\Service\Attribute\Required;

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

    public  function verifyUserEmail($email,$code){
        try {
            $user = User::where('email',$email)->first();
            if ($user) {
                if ($user->verify_token == $code) {
                    $sms_token = rand(111111,999999);
                    $user->email_verified_at = Carbon::now();
                    $user->sms_token = $sms_token;
                    $user->save();                   
                    $this->verifyPhone($user->telephone,$sms_token,$user->user_id);
                    return response()->json([
                        'status'=>'success',
                        'email'=>$email,
                        'token'=>$sms_token,
                        'id'=>$user->id
                    ],200);          
                   
                } 
                return redirect()->back()->withErrors('Error','unknown code entered');
            }
            return redirect()->route('login')->withErrors('Error','unauthorised');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function forgotPassword(Request $request){
        $data = $request->data;
        $user = User::where('email',$data)->orWhere('telephone',$data)->first();
        if($user){
            $email = $user->email;
            $code = rand(111111,999999);
            $user->verify_token = $code;
            $user->save();
            Mail::to($email)->send(new ConfirmMail($data,$code));
            // return redirect()->route('verify')->with(['email'=>$request->email]);

            return response()->json([
                'status'=>'success',
                'email'=>$email,
                'token'=>$code
            ],200);

        }
        return response()->json([
                'status'=>'failed',
                'message'=>'No user With such An Email'
        ],200);
    }

    public function verifyUserPhone(Request $request){
        $data = $request->email;
        $user = User::where('email',$data)->orWhere('telephone',$data)->first();
        if($user && $user->sms_token == $request->sms_token){
            $user->sms_verified_at = Carbon::now();
            $user->save();
            return response()->json([
                'status'=>'success',
                'email'=>$user->email,
                'message'=>''
            ],200);
        }
        return response()->json([
            'status'=>'failed',
            'message'=>'wrong token'
            ],200);
    }
//forgot password reset
    public function resetPassword(Request $request){
        $rules = array(
            'password'=>'required|min:6|confirmed',
            'password_confirmation'=>'required'
        );
        $validated = $request->validate($rules);
        $user = User::find($request->id);
        if($user){
            $user->password = bcrypt($request->password);
            $user->save();
            $this->authenticate($request);
        }
        return response()->json([
            'status'=>'failed',
            'message'=>'unmatching passwords'
            ],200);
    }

    public function passwordReset(Request $request){
        $rules = array(
            'new_password'=>'required|min:6|confirmed',
            'new_password_confirmation'=>'required',
            'old_password'=>'required'
        );
        $validated = $request->validate($rules);
        $user = User::find(Auth::id());
        if($user){
            $check_password = Hash::check($request->old_password, $user->password);
            if($check_password){
                $user->password = bcrypt($request->password);
                $user->save();
                $this->authenticate($request);

            }
            Auth::logout();
        }
        return response()->json([
            'status'=>'failed',
            'message'=>'unmatching passwords'
            ],200);
    }

   
   
}
