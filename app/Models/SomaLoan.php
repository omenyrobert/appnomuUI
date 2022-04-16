<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SomaLoan extends Model
{
    use HasFactory;

    public function repayments(){
        return $this->morphMany(Repayment::class,'repaymentable');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function student(){
        return $this->belongsTo(Student::class);
    }
}
