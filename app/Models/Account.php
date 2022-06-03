<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    protected $table = 'user_account';

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function loans(){
        return $this->hasMany(Loan::class);
    }

    public function savinggs(){
        return $this->hasMany(Savingg::class);

    }

    public function withdraws(){
        return $this->hasMany(Withdraw::class);
    }

    public function somaLoans(){
        return $this->hasMany(SomaLoan::class);
    }

    public function BusinessLoans(){
        return $this->hasMany(BusinessLoan::class);
    }
}
