<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SMS extends Model
{
    use HasFactory;
    protected $table = 'smssent';

    public function user(){
        return $this->belongsTo(User::class);
    }
}
