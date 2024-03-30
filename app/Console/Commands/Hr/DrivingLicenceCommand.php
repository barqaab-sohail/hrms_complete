<?php

namespace App\Console\Commands\Hr;

use Illuminate\Console\Command;
use App\Models\Hr\HrEmployee;
use App\Notifications\Hr\Email\DrivingLicenceNotification;
use App\User;

class DrivingLicenceCommand extends Command
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
            if($employee->hrDesignation->last()->name??''=='Driver'){
                
                if($employee->hrDriving->licence_expiry??''!=''){
                    if($employee->hrDriving->licence_expiry<$toDay){
                
                        $administrator = User::where('email', 'sohail.afzal@barqaab.com')->first();
                        $hrManager = User::where('email', 'hr@barqaab.com')->first();
                        $hrAssistance = User::where('email', 'athar@barqaab.com')->first();
                        $rasheed = User::where('email', 'muhammadrasheed2009@gmail.com')->first();
                        $administrator->notify(New DrivingLicenceNotification($employee));
                        $rasheed->notify(New DrivingLicenceNotification($employee));
                        $hrManager->notify(New DrivingLicenceNotification($employee));
                        $hrAssistance->notify(New DrivingLicenceNotification($employee));
                       
                       //print_r($employee);
                        //echo $employee->first_name.' '.$employee->last_name;
                        //echo '<br>';

 //Blue Host Command 
//cd /home1/barqaabc/public_html/hrms && php artisan schedule:run >> /dev/null 2>&1

//cd /home/barqaabp/public_html/hrms && php artisan licence:expiry >> /dev/null 2>&1

                    }
                }
            }
            
        }


    }
}
