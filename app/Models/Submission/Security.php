<?php
// app/Models/Security.php

namespace App\Models\Submission;

use App\Models\Common\Bank;
use App\Models\Common\Client;
use App\Models\Common\Partner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Security extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type',
        'bid_security_type',
        'favor_of',
        'date_issued',
        'expiry_date',
        'amount',
        'project_name',
        'document_path',
        'remarks',
        'reference_number',
        'status',
        'client_id',
        'submitted_by',
        'bank_id',
    ];

    protected $casts = [
        'date_issued' => 'date',
        'expiry_date' => 'date',
        'amount' => 'decimal:2',
    ];


    public function submittedBy()
    {
        return $this->belongsTo(Partner::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    // Scope for active securities
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope for expired securities
    public function scopeExpired($query)
    {
        return $query->where('status', 'expired');
    }

    // Check if security is expired
    public function isExpired()
    {
        if ($this->type === 'bid_security' && $this->bid_security_type !== 'bank_guarantee') {
            return false; // Pay Order/CDR doesn't expire
        }

        return $this->expiry_date && $this->expiry_date->isPast();
    }
}
