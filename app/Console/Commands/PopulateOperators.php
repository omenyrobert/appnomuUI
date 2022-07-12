<?php

namespace App\Console\Commands;

use App\Http\Traits\AirtimeTrait;
use App\Models\AirtimeOperator;
use Illuminate\Console\Command;

class PopulateOperators extends Command
{
    use AirtimeTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'populate:operators';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A Command to populate operators table';

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
        $this->populateOperators();
    }

    public function populateOperators(){
        $this->info('fetching operators');
        $operators = $this->getTopupOperators();
        $this->info('fetched operators');
        foreach($operators as $operator){
            $this->info("populating $operator->name");
            $new_operator = new AirtimeOperator();
            $new_operator->name = $operator->name;
            $new_operator->operator_code = $operator->operatorId;
            $new_operator->country_id = $operator->country->isoName;
            $new_operator->logo_url = $operator->logoUrls ?  $operator->logoUrls[0]: '';
            $new_operator->save();
            $this->info("populated $operator->name");
        }
        $this->info("populated operators");
        return;
    }
}
