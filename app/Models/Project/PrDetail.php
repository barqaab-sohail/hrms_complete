<?php

namespace App\Models\Project;

use App\Models\Project\Progress\PrAchievedProgress;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PrDetail extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;


    protected $fillable = ['name', 'client_id', 'commencement_date', 'contractual_completion_date', 'actual_completion_date', 'sub_projects', 'pr_status_id', 'pr_role_id', 'contract_type_id', 'pr_division_id', 'project_no', 'share'];


    //default value of pr_status_id=1
    protected $attributes = [
        'pr_status_id' => 1 //default value of pr_status_id is 1 
    ];

    //get value of pr_status_id and show in string as per statusOptions
    public function getPrStatusIdAttribute($attribute)
    {
        return $this->statusOptions()[$attribute];
    }

    public function statusOptions()
    {
        return [
            1 => 'In Progress',
            2 => 'Completed',
            3 => 'Suspended',
        ];
    }


    public function client()
    {
        return $this->belongsTo('App\Models\Common\Client');
    }

    public function prCost()
    {
        return $this->hasOne('App\Models\Project\Cost\PrCost');
    }

    public function prRole()
    {
        return $this->belongsTo('App\Models\Project\PrRole');
    }

    public function getFormattedCommencementDateAttribute()
    {
        return \Carbon\Carbon::parse($this->commencement_date)->format('M d, Y');
    }

    public function prSubProject()
    {
        return $this->hasMany('App\Models\Project\PrSubProject');
    }
    public function invoices()
    {
        return $this->hasMany('App\Models\Project\Invoice\Invoice');
    }

    public function invoiceCost()
    {
        return $this->hasManyThrough(
            'App\Models\Project\Invoice\InvoiceCost',
            'App\Models\Project\Invoice\Invoice',
            'pr_detail_id',
            'id',
            'id',
            'id',
        );
    }
    public function invoiceCostWOEsc()
    {
        return $this->hasManyThrough(
            'App\Models\Project\Invoice\InvoiceCost',
            'App\Models\Project\Invoice\Invoice',
            'pr_detail_id',
            'id',
            'id',
            'id',
        )->where('invoice_type_id', '!=', 3);
    }

    public function latestInvoiceMonth()
    {
        return $this->hasOneThrough(
            'App\Models\Project\Invoice\InvoiceMonth',          //Final Model l
            'App\Models\Project\Invoice\Invoice',              //Model Through Access Final Model (Immediate Model)  
            'pr_detail_id',                                     //Forein Key in Immediate Model of This Model (PrDetail)
        )->orderby('invoices.invoice_date', 'desc');
    }

    public function latestExpenseMonth()
    {
        return $this->hasOne('App\Models\Project\PrMonthlyExpense')->orderby('month', 'desc');
    }

    public function latestPaymentMonth()
    {
        return $this->hasOne('App\Models\Project\Payment\PaymentReceive')->orderby('payment_date', 'desc');
    }
}
