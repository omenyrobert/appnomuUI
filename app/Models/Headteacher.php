<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Headteacher extends Model
{
    use HasFactory;

    public function students()
    {
        return $this->belongsToMany(Student::class,'headteacher_student')->withPivot('student_admission_num')->withTimestamps();
    }

    public function district(){
        return $this->belongsTo(District::class);
    }

    public function somaLoan(){
        return $this->belongsTo(SomaLoan::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
