<?php

namespace App\DataTables\Hr;

use App\Models\Hr\HrEmployee;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class EmployeeListDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        
        //first sort with respect to Designation
        //$designations = employeeDesignationArray();
        // $query = $query->sort(function ($a, $b) use ($designations) {
        //   $pos_a = array_search($a->designation??'', $designations);
        //   $pos_b = array_search($b->designation??'', $designations);
        //   return $pos_a - $pos_b;
        // });
   
        return datatables()
            ->eloquent($query)
            ->filterColumn('full_name', function($query, $keyword) {
                    $sql = "CONCAT(hr_employees.first_name,'-',hr_employees.last_name)  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
            })
           
            ->addColumn('full_name',function($data){
                
                if(Auth::user()->can('hr edit record')){
                    $fullName = '<a href="'.route('employee.edit',$data->id).'" style="color:grey">'.$data->full_name.'</a>';
                    return $fullName;
                }else{
                    return $data->full_name;
                }

            })
            ->addColumn('project',function($data){
                
               $project = isset($data->employeeProject->last()->name)?$data->employeeProject->last()->name:'';
               if($project==''){
                    return '';
               }
               else if($project =='overhead'){
                    return $data->employeeOffice->last()->name??'';

                }else{

                    if(Auth::user()->hasPermissionTo('hr edit documentation')){
                    $link = '<a href="'.route('project.edit',$data->employeeProject->last()->id??'').'" style="color:grey">'.$data->employeeProject->last()->name??''.'</a>';
                     return $link;
                    } else{
                        return $data->employeeProject->last()->name??'';
                    }
                }
            })
            ->addColumn('date_of_birth',function($data){
                return \Carbon\Carbon::parse($data->date_of_birth)->format('M d, Y');
            })
            ->addColumn('date_of_joining',function($data){
                return \Carbon\Carbon::parse($data->employeeAppointment->joining_date??'')->format('M d, Y');
            })
            ->addColumn('mobile',function($data){
                return $data->hrContactMobile->mobile??'';
            })
            ->addColumn('edit', function($data){

                if(Auth::user()->hasPermissionTo('hr edit documentation')){
                    
                    $button = '<a class="btn btn-success btn-sm" href="'.route('employee.edit',$data->id).'"  title="Edit"><i class="fas fa-pencil-alt text-white "></i></a>';

                    return $button;
                } 

            })
            ->addColumn('delete', function($data){
                if(Auth::user()->hasRole('Super Admin')){
                    $button = '<form  id="formDeleteContact'.$data->id.'"  action="'.route('employee.destroy',$data->id).'" method="POST">'.method_field('DELETE').csrf_field().'
                             <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you Sure to Delete\')" href= data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-trash-alt"></i></button>
                             </form>';
                    return $button;
                }
            })
            ->rawColumns(['full_name','project','edit','delete'])
            ;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Hr/EmployeeListDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(HrEmployee $model)
    {
    
        $data = $model->with('employeeDesignation','employeeProject','employeeOffice','employeeAppointment','hrContactMobile')->newQuery();

        //     //second sort with respect to Hr Status
        //     $hrStatuses = array('On Board','Resigned','Terminated','Retired','Long Leave','Manmonth Ended','Death');

        //     $data = $data->sort(function ($a, $b) use ($hrStatuses) {
        //       $pos_a = array_search($a->hr_status_id??'', $hrStatuses);
        //       $pos_b = array_search($b->hr_status_id??'', $hrStatuses);
        //       return $pos_a - $pos_b;
        //     });

        return $data;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('hr_employees-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)
                    ->buttons(
                        Button::make('export')
                    );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        
        return [
            // Column::computed('action')
            //       ->exportable(false)
            //       ->printable(false)
            //       ->width(60)
            //       ->addClass('text-center'),
           
            
            'employee_no',
            ['data' => 'full_name', 'title' => 'Employee Name'],
            ['data' => 'designation', 'title' => 'Designation/Position','searchable'=>false],
            ['data' => 'project', 'title' => 'Project/Office'],
            'date_of_birth',
            ['data' => 'hr_status_id', 'title' => 'Status'],
            ['data' => 'cnic', 'title' => 'CNIC'],
            'date_of_joining',
            'mobile',
            Column::make('edit'),
            Column::make('delete')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Hr/EmployeeList_' . date('YmdHis');
    }
}
