<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function transactionable(){
        return $this->morphTo();
    }

    // public function paymentable(){
    //     return $this->morphTo(__FUNCTION__, 'paymentable_type', 'paymentable_id');
    // }
}
