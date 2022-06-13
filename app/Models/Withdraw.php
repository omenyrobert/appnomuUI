<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    use HasFactory;

    

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function account(){
        return $this->belongsTo(Account::class);
    }

    public function withdrawFee(){
        return $this->belongsTo(withdrawFee::class);
    }

    public function transaction(){
        return $this->morphOne(Transaction::class,'transactionable');
    }
}
