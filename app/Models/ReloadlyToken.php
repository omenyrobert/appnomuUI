<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReloadlyToken extends Model
{
    use HasFactory;

    public function tokenable(){
        return $this->morphTo();
    }
    
}
