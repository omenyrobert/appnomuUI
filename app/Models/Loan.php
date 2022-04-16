<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;
    protected $table = 'userloans';
    public function loan_product(){
        return $this->belongsTo(LoanProduct::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

}
