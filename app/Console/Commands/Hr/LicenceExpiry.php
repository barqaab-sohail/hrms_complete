<?php

namespace App\Console\Commands\Hr;

use Illuminate\Console\Command;
use App\Models\Hr\HrEmployee;

class LicenceExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'licence:expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It is notify expiry of licence';

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
        $employees = HrEmployee::where('hr_status_id',1)->with('hrDesignation')->get();
        $toDay = \Carbon\Carbon::now()->format('Y-m-d');
        
        foreach ($employees as $key => $employee) {   
            if($employee->hrDesignation->last()->name=='Driver'){
                
                if($employee->hrDriving->licence_expiry??''!=''){
                    if($employee->hrDriving->licence_expiry<$toDay){
                
                        echo $employee->hrDriving->licence_expiry??'';
                        echo $employee->first_name;
                        echo $employee->last_name;
                        echo '<br>';
                    }
                }
            }
            
        }





        echo 'testing command';
    }
}
