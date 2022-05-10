<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RefferalController extends Controller
{
   public function index(){
       $user = User::find(Auth::id());
       if($user){
        $refferals = User::where('refferer',$user->user_id)->get();
        return view('refferals.index',['user'=>$user,'refferals'=>$refferals])->with('page','My Refferals');
       }
       return redirect()->route('login');
   }
}
