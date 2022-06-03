<?php

namespace App\Http\Controllers;

use App\Models\Savingg;
use App\Models\SavingSubCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavingController extends Controller
{
    public function index(){
        $user = User::find(Auth::id());
        if($user){
            $savings = $user->role == 'admin' ? Savingg::latest()->paginate(10)
                : $user->savings()->latest()->paginate(10);
            $categories = SavingSubCategory::with('savingCategory')->get();
            // dd($categories);
            return view('savings.index',
                ['savings'=>$savings,
                'user'=>$user,
                'categories'=>$categories
                ]
            )->with('page','All | Savings');
        }
        return redirect()->route('login');
    }

    public function create(){
        $user = User::find(Auth::id());
        if($user){
            $savings = $user->role == 'admin' ? Savingg::latest()->paginate(10)
                : $user->savings()->latest()->paginate(10);
            return view('savings.index')->with('page','All | Savings');
        }
        return redirect()->route('login');
    }
}
