<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavingSubCategory extends Model
{
    use HasFactory;
    protected $table = 'savingsubcategories';

    public function savingCategory(){
        return $this->belongsTo(SavingCategory::class,'cate_id','cate_id');
    }

    public function savings(){
        return $this->hasMany(Saving::class,'SubCateId','SubCateId');
    }
}
