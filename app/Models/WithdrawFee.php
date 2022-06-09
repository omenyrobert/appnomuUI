<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawFee extends Model
{
    use HasFactory;

    public function withdraws(){
        return $this->hasMany(Withdraw::class);
    }
}
