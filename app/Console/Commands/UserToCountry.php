<?php

namespace App\Console\Commands;

use App\Models\Country;
use App\Models\User;
use Illuminate\Console\Command;

class UserToCountry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:country';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A Command to connect a user to a country';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->userCountry();
        return 0;
    }

    private function userCountry(){
        $this->info('fetching all users');
        $users = User::all();
        $this->info('getting uganda from db');
        $country = Country::where('ISO','=','UG')->first();
        if($country){
            $this->info('attach each user to a country');
            foreach($users as $user){          
                $user->country()->associate($country);
                $user->save();
            }

        }
        $this->error('Uganda doesnt exist as a country');
    }
}
