<?php

namespace App\Console\Commands\Hr;

use Illuminate\Console\Command;
use App\Models\Hr\HrEmployee;
use App\Notifications\Hr\Email\CnicExpiryNotification;
use App\User;

class CnicExpiryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnic:expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify that Employee CNIC has been expired';

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
        $employees = HrEmployee::where('hr_status_id',1)->with('employeeAppointment','employeeProject')->get();
        
        $today = \Carbon\Carbon::now();
        foreach ($employees as $key => $employee) {   
                
            if($employee->cnic_expiry??''!=''){
                if($employee->cnic_expiry<$today){
            
                    $administrator = User::where('email', 'sohail.afzal@barqaab.com')->first();
                    $hrManager = User::where('email', 'hr@barqaab.com')->first();
                    $hrAssistance = User::where('email', 'athar@barqaab.com')->first();
                    $rasheed = User::where('email', 'muhammadrasheed2009@gmail.com')->first();
                    //$administrator->notify(New CnicExpiryNotification($employee));
                    // $rasheed->notify(New CnicExpiryNotification($employee));
                    // $hrManager->notify(New CnicExpiryNotification($employee));
                    // $hrAssistance->notify(New CnicExpiryNotification($employee));
                    
                    // echo $employee->first_name.' '.$employee->last_name.'- Project: '.$employee->employeeProject->name;
                    // echo '<br>';
                }
            }
            
        }
    }
}
