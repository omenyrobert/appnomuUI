<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirtimeRate extends Model
{
    use HasFactory;

    public function transactions(){
        return $this->morphMany(Transaction::class,'paymentable');
    }


}
