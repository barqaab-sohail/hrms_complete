<?php

namespace App\Http\Requests\Leave;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Leave\Leave;
use App\Models\Hr\HrEmployee;

class LeaveStore extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    private $yearFrom, $yearTo, $leaveBalance;

    public function __construct(\Illuminate\Http\Request $request)
    {
        $timeFrom=strtotime($request->from);
        $this->yearFrom = date("Y",$timeFrom);
        $timeTo=strtotime($request->to);
        $this->yearTo = date("Y",$timeTo);
        $startDate = $this->yearTo.'-01-01';
        $endDate = $this->yearFrom.'-12-31';

        $employee = HrEmployee::find($request->hr_employee_id);
        $joiningDate = $employee->employeeAppointment->joining_date;

        //check total casual leave balance
        $totalCasualLeave=0;
        if($joiningDate<$startDate){
            $totalCasualLeave =12;
        }else{
            $startTimeStamp = strtotime($joiningDate);
            $endTimeStamp = strtotime($endDate);

            $timeDiff = abs($endTimeStamp - $startTimeStamp);

            $numberDays = $timeDiff/86400;  // 86400 seconds in one day

            // and you might want to convert to integer
            $numberDays = round($numberDays);

            $totalCasualLeave = round(12 *  $numberDays / 365);
        }


        //check current year annual leave balance
        $category = $employee->employeeCategory->last()->name??'';
        if($category == 'A'){
        $categoryADate = $employee->employeeCatA->effective_date;//'2022-05-01';

            $currentYearAnnualLeave=0;
            if($categoryADate<$startDate){
                $currentYearAnnualLeave =18;
            }else{
                $startTimeStamp = strtotime($categoryADate);
                $endTimeStamp = strtotime($endDate);

                $timeDiff = abs($endTimeStamp - $startTimeStamp);

                $numberDays = $timeDiff/86400;  // 86400 seconds in one day

                // and you might want to convert to integer
                $numberDays = round($numberDays);

                $currentYearAnnualLeave = round(18 *  $numberDays / 365);
            }
        }

        //
        if($request->le_type_id==1){

        $this->leaveBalance = $totalCasualLeave - Leave::where('hr_employee_id',$request->hr_employee_id)->where('le_type_id',1)->whereDate('from', ">=", $startDate)->whereDate('to', "<=",$endDate)->sum('days');

        }elseif($request->le_type_id==2){ //Check Annual Leave Balance only current year.
            $totalAnnualLeave =  $currentYearAnnualLeave + $employee->leAccumulative->accumulative_total??'';
            $this->leaveBalance = $totalAnnualLeave - Leave::where('hr_employee_id',$request->hr_employee_id)->where('le_type_id',2)->whereDate('from', ">=", $startDate)->whereDate('to', "<=",$endDate)->sum('days');
        }elseif($request->le_type_id==3){
            $this->leaveBalance = 365 - Leave::where('hr_employee_id',$request->hr_employee_id)->where('le_type_id',3)->whereDate('from', ">=", $startDate)->whereDate('to', "<=",$endDate)->sum('days');
        }elseif($request->le_type_id==4){
            $this->leaveBalance = 50 - Leave::where('hr_employee_id',$request->hr_employee_id)->where('le_type_id',4)->whereDate('from', ">=", $startDate)->whereDate('to', "<=",$endDate)->sum('days');
        }
    }
   
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
   
    public function rules()
    {

        $rules = [
            'hr_employee_id'=> 'required',
            'le_type_id'=> 'required',
            'from'=> 'required|date|unique_with:leaves,hr_employee_id,'.session('leave_id'),
            'to'=> 'required|after_or_equal:from||unique_with:leaves,hr_employee_id,'.session('leave_id'),
            'days'=>'required|numeric|not_in:0|max:'.$this->leaveBalance,
            'reason'=> 'required',
        ];

       return $rules;
    }

    public function messages()
    {  
        return [
            'to.after_or_equal' => "To date must be equal or greater than from date",
            'days.max'=>'You have not '.$this->days.' days Leave Balance',
            'days.not_in'=>'Days must be greater than zero'.$this->from,
            'from.unique_with'=> 'These days Employee Leave already exist ',
            'to.unique_with'=> 'These days Employee Leave already exist ',

        ];
    }

    protected function getValidatorInstance()
    {
    $data = $this->all();
    $data['from'] = \Carbon\Carbon::parse($this->from)->format('Y-m-d');
    $data['to'] = \Carbon\Carbon::parse($this->to)->format('Y-m-d');
    $this->getInputSource()->replace($data);

    /*modify data before send to validator*/
    return parent::getValidatorInstance();
    }

}
