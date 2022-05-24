<?php

namespace App\Http\Controllers;

use App\Models\SavingCategory;
use App\Models\SavingSubCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavingSubCategoryController extends Controller
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
                $main_categories = SavingCategory::all();
                $categories = SavingSubCategory::oldest()->paginate(10);
                dd($categories);
                return view('categories.savings.index',['main_categories'=>$main_categories,'categories'=>$categories,'user'=>$user])->with('page','Saving | Sub |Categories');
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
                $main_category = SavingCategory::where('cate_id',$request->category_id)->first();
                if($main_category){
                    $category = new SavingSubCategory();
                    $category->savingCategory()->associate($main_category);
                    $category->Saving_Period = $request->period;
                    $category->Interest = $request->interest;
                    $category->SubCate_Id = 'Sub-'.rand(0001,9999).'-Cat';
                    $category->save();
                    if($category){
    
                        return redirect()->back();
                    }
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
                
                $category = SavingSubCategory::findOrFail($id);
                $category->Saving_Period = $request->period;
                $category->Interest = $request->interest;
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
                
                $category = SavingSubCategory::findOrFail($id);
                $category->delete();

                    return redirect()->back();
                
            }
            return redirect()->route('login');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
