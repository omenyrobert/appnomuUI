<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirtimeOperator extends Model
{
    use HasFactory;

    public function country(){
        return $this->belongsTo(Country::class,'country_id','ISO');
    }

    public function airtimeRates(){
        return $this->belongsToMany(AirtimeRate::class,'operator_rate','operator_id','rate_id')->withPivot('active')->withTimestamps();
    }
}
