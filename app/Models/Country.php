<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    public function users(){
        return $this->hasMany(User::class,'country_id','ISO');
    }

    public function operators(){
        return $this->hasMany(AirtimeOperator::class,'country_id','ISO');
    }
}
