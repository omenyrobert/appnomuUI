<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $value='12345678';
        // $user = User::create(['name'=>'admin','email'=>'admin@appnoomu.com','password'=>Hash::make($value)]);
        $user = DB::table('sysusers')->insert([
            'id'=>1000,
            'name'=> "Dibossman",
            'user_id'=> 1,
            'telephone'=> '0778991059',
            'email'=>'isaacomega16@gmail.com',
            'password'=> md5('1234567890'),
            'email_verified_at'=> \Carbon\Carbon::now(),
            'created_at'=>\Carbon\Carbon::now(),
            'role' => 'admin'
        ]);


    }
}
