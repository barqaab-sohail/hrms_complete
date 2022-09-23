<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PrDetail extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;


    protected $fillable = ['name', 'client_id', 'commencement_date', 'contractual_completion_date', 'actual_completion_date', 'sub_projects', 'pr_status_id', 'pr_role_id', 'contract_type_id', 'pr_division_id', 'project_no', 'share'];


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

    public function latestInvoiceMonth()
    {
        return $this->hasOneThrough(
            'App\Models\Project\Invoice\InvoiceMonth',          //Final Model l
            'App\Models\Project\Invoice\Invoice',              //Model Through Access Final Model (Immediate Model)  
            'pr_detail_id',                                     //Forein Key in Immediate Model of This Model (PrDetail)
        )->orderby('invoices.id', 'desc');
    }
}
