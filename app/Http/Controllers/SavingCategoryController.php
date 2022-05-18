<?php

namespace App\Http\Controllers;

use App\Models\SavingCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavingCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $user = User::find(Auth::id());
            if($user && $user->role== 'admin'){
                $categories = SavingCategory::orderBy('lowerlimit','asc')->paginate(10);
                return view('categories.savings.index',['categories'=>$categories,'user'=>$user])->with('page','Saving | Categories');
            }
            return redirect()->route('login');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $user = User::find(Auth::id());
            if($user && $user->role== 'admin'){
                $category = new SavingCategory();
                $category->lowerlimit = $request->lower_limit;
                $category->upperlimit = $request->upper_limit;
                $category->cate_id = 'CAT-'.rand(0001,9999);
                $category->save();
                if($category){

                    return redirect()->back();
                }
            }
            return redirect()->route('login');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $user = User::find(Auth::id());
            if($user && $user->role== 'admin'){
                $category = SavingCategory::findOrFail($id);
                $category->lowerlimit = $request->lower_limit;
                $category->upperlimit = $request->upper_limit;
                $category->save();
                if($category){

                    return redirect()->back();
                }
            }
            return redirect()->route('login');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = User::find(Auth::id());
            if($user && $user->role== 'admin'){
                $category = SavingCategory::findOrFail($id);
                $category->delete();

                    return redirect()->back();
            }
            return redirect()->route('login');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
