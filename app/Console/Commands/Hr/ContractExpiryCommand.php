<?php

namespace App\Console\Commands\Hr;

use Illuminate\Console\Command;
use App\Models\Hr\HrEmployee;
use App\Notifications\Hr\Email\ContractExpiryNotification;
use App\User;

class ContractExpiryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contract:expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify that Employee Contract Agreement has been expired';

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
        
        $nextTenDays = \Carbon\Carbon::now()->addDays(10)->format('Y-m-d');
        foreach ($employees as $key => $employee) {   
                
            if($employee->employeeAppointment->expiry_date??''!=''){
                if($employee->employeeAppointment->expiry_date<$nextTenDays){
            
                    $administrator = User::where('email', 'sohail.afzal@barqaab.com')->first();
                    $hrManager = User::where('email', 'hr@barqaab.com')->first();
                    $hrAssistance = User::where('email', 'athar@barqaab.com')->first();
                    $rasheed = User::where('email', 'muhammadrasheed2009@gmail.com')->first();
                    $administrator->notify(New ContractExpiryNotification($employee));
                    $rasheed->notify(New ContractExpiryNotification($employee));
                    $hrManager->notify(New ContractExpiryNotification($employee));
                    $hrAssistance->notify(New ContractExpiryNotification($employee));
                    //echo $employee->first_name.' '.$employee->last_name.'- Project: '.$employee->employeeProject->name;
                    //echo '<br>';
                }
            }
            
        }
    }
}
