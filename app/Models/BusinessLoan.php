<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessLoan extends Model
{
    use HasFactory;
    protected $table = 'business_loans';
    public function business(){
        return $this->belongsTo(Business::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function repayments(){
        return $this->morphMany(Repayment::class,'repaymentable');
    }

}
