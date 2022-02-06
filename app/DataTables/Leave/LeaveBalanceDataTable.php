<?php

namespace App\DataTables\Leave;

use App\Models\Hr\HrEmployee;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class LeaveBalanceDataTable extends DataTable
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
            ->addColumn('full_name', '{{$first_name}} {{$last_name}}')
            ->addColumn('action', 'Full Name');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Leave/LeaveBalance $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(HrEmployee $model)
    {
        return $model->newQuery();
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
                        Button::make('export'),
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
            
            Column::make('id'),
            Column::make('employee_no'),
            Column::make('full_name'),
            Column::computed('action')
                  ->exportable(fasle)
                  ->printable(fasle)
                  ->width(60)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'HrEmployee_' . date('YmdHis');
    }

    protected $exportColumns = [
    'full_name',
    'action',
    ];
}
