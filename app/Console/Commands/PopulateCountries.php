<?php

namespace App\Console\Commands;

use App\Http\Traits\AirtimeTrait;
use App\Models\Country;
use Illuminate\Console\Command;

class PopulateCountries extends Command
{
    use AirtimeTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'populate:countries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A command to populate countries table';

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
        $countries = $this->fetchCountries();
        $this->populateCountriesTable($countries);
    }

    public function fetchCountries(){
        $this->info('fetching countries');
        $countries = $this->getTopupCountries();
        $this->info('fetched countries');
        return $countries;
    }

    public function populateCountriesTable($countries){
        $this->info('populating table per country');
        foreach($countries as $country){
            $this->info("populating $country->name");
            $new_country = new Country();
            $new_country->ISO = $country->isoName;
            $new_country->name = $country->name; 
            $new_country->flag_url = $country->flag;
            $new_country->currency_code = $country->currencyCode; 
            $new_country->currency_name = $country->currencyName; 
            $new_country->save();
            $this->info("successfully populated $country->name");
        }
        $this->info("finished populating countries");
        return;
    }
}
