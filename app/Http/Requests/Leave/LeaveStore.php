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
            $numberDays = intval($numberDays);

            $totalCasualLeave = intval(12 *  $numberDays / 365);
        }

        if($request->le_type_id==1){

        $this->leaveBalance = $totalCasualLeave - Leave::where('hr_employee_id',$request->hr_employee_id)->where('le_type_id',1)->whereDate('from', ">=", $startDate)->whereDate('to', "<=",$endDate)->sum('days');

        }elseif($request->le_type_id==2){
            $this->leaveBalance = 18 - Leave::where('hr_employee_id',$request->hr_employee_id)->where('le_type_id',2)->whereDate('from', ">=", $startDate)->whereDate('to', "<=",$endDate)->sum('days');
        }elseif($request->le_type_id==3){
            $this->leaveBalance = 365 - Leave::where('hr_employee_id',$request->hr_employee_id)->where('le_type_id',3)->whereDate('from', ">=", $startDate)->whereDate('to', "<=",$endDate)->sum('days');
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
            'from'=> 'required|date',
            'to'=> 'required|after_or_equal:from',
            'days'=>'required|numeric|max:'.$this->leaveBalance,
            'reason'=> 'required',
        ];

       return $rules;
    }

    public function messages()
    {  
        return [
            'to.after_or_equal' => "To date must be equal or greater than from date - ".$this->yearFrom . '-'. $this->yearTo,
            
        ];
    }

}
