<?php

namespace App\DataTables\Leave;

use App\Models\Leave\Leave;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class LeaveListDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('employee_no', function($data){   
                $employee_no = $data->hrEmployee->employee_no;
                return $employee_no;
            })
            ->addColumn('full_name', function($data){
                $full_name = $data->hrEmployee->first_name . ' '. $data->hrEmployee->last_name;

                return $full_name;
            })
            ->addColumn('designation',function($data){
                return $data->employeeDesignation->last()->name??'';
            })
            ->editColumn('from',function($data){
                return \Carbon\Carbon::parse($data->from)->format('M d, Y');
            })
            ->editColumn('to',function($data){
                return \Carbon\Carbon::parse($data->to)->format('M d, Y');
            })
            ->addColumn('leave_type',function($data){
                return $data->leType->name??'';
            })
        ->addColumn('status',function($data){

                $status='';
                $color = '';

                if($data->leSanctioned){
                    $status = leaveStatusType($data->leSanctioned->le_status_type_id);
                    if($data->leSanctioned->le_status_type_id==1){
                         $color='btn-success';
                     }else{
                         $color = 'btn-danger';
                     }
                   
                }else{
                    $status = 'Pending';
                    $color = 'btn-danger';
                }

            return '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Edit" class="edit btn '.$color.'  btn-sm editStatus">'.$status.'</a>';
                
            })
           
            ->addColumn('edit', function($data){
       
            $button = '<a class="btn btn-success btn-sm" href="'.route('leave.edit',$data->id).'"  title="Edit"><i class="fas fa-pencil-alt text-white "></i></a>';

            return $button;  

            })
            ->addColumn('delete', function($data){

                $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteLeave">Delete</a>';                            
                return $btn;

            })
            ->rawColumns(['status', 'edit','delete'])
            ;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Leave\LeaveListDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Leave $model)
    {
        return $model->with('hrEmployee','employeeDesignation','leType')->orderBy('from', 'desc')->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('leaves-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)
                    ->buttons(
                        Button::make('export'),
                        Button::make('reload')
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

            Column::make('employee_no'),
            Column::make('full_name'),
            Column::make('designation'),
            Column::make('from'),
            Column::make('to'),
            Column::make('leave_type'),
            Column::make('status'),
            Column::make('edit')->exportable(false)->printable(false),
            Column::make('delete')->exportable(false)->printable(false),

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Leave\LeaveList_' . date('YmdHis');
    }

    
}

