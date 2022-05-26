<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirTime extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function airTimeRate(){
        return $this->belongsTo(AirtimeRate::class,'air_time_rate_id','id');
    }

    public function transaction(){
        return $this->morphOne(Transaction::class,'paymentable');
    }
}
