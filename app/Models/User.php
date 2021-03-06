<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Savings;

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
  
    //a user can be a parent with many students
    public function students(){
        return $this->hasMany(Student::class,'user_id','id');
    }
    //user has many headteachers 
    // public function headteachers(){
    //     return $this->hasManyThrough(Headteacher::class ,Student::class,'user_id','student_id','id','id');
    // }

    public function somaLoans(){
        return $this->hasMany(SomaLoan::class);
    }

    public function businessLoans(){
        return $this->hasMany(BusinessLoan::class);
    }

    public function businesses(){
        return $this->hasMany(Business::class);
    }

    public function businessCredentials(){
        $this->hasMany(BusinessCredential::class);
    }

    public function alliances(){
        return $this->hasMany(Alliance::class);
    }

    public function identification(){
        return $this->hasOne(Identification::class);
    }
    public function account(){
        return $this->hasOne(Account::class);
    }
    public function district(){
        return $this->belongsTo(District::class);
    }

    public function country(){
        return $this->belongsTo(Country::class,'country_id','ISO');
    }

    public function repayments(){
        return $this->hasMany(Repayment::class);
    }

    public function loans(){
        return $this->hasMany(Loan::class);
    }

    public function savings(){
        return $this->hasMany(Saving::class);
    }
    
    public function withdraws(){
        return $this->hasMany(Withdraw::class);
    }

    public function headteachers(){
        return $this->hasMany(Headteacher::class);
    }

    public function transactions(){
        return $this->hasMany(Transaction::class);
    }

    public function airtimes(){
        return $this->hasMany(AirTime::class);
    }
    public function electricities(){
        return $this->hasMany(Electricity::class);
    }
    public function payments(){
        return $this->hasMany(Payment::class);
    }


}
