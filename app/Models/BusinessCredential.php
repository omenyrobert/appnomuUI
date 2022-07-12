<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessCredential extends Model
{
    use HasFactory;

    public function business(){
        return $this->belongsTo(Business::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function district(){
        return $this->belongsTo(District::class);
    }

    public function businessLoan(){
        return $this->belongsTo(BusinessLoan::class,'business_loan_id','id');
    }
}
