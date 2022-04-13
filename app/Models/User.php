<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'sysusers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    //user can have many loans
    public function loans(){
        return $this->hasMany(Loan::class);
    }
    //a user can be a parent with many students
    public function students(){
        return $this->hasMany(Student::class,'user_id','id');
    }
    //user has many headteachers 
    public function headteachers(){
        return $this->hasManyThrough(Headteacher::class ,Student::class,'user_id','student_id','id','id');
    }
}
