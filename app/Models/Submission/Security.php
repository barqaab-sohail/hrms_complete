<?php
// app/Models/Security.php

namespace App\Models\Submission;

use App\Models\Common\Bank;
use App\Models\Common\Client;
use App\Models\Common\Partner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SecuritiesExport;
use PDF;
use DateTimeInterface;

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
        'date_issued' => 'datetime',
        'expiry_date' => 'datetime',
        'amount' => 'decimal:2',
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d'); // Format as simple date without time
    }

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

    // Export securities to Excel
    public static function exportToExcel()
    {
        return Excel::download(new SecuritiesExport, 'securities.xlsx');
    }

    //Export securities to PDF
    public static function exportToPDF()
    {

        $securities = self::with(['client', 'bank', 'submittedBy'])->get();

        // Load the view and generate PDF
        $pdf = Pdf::loadView('submission.security.securities-pdf', [
            'securities' => $securities,
            'title' => 'Securities Report',
            'date' => now()->format('F j, Y')
        ]);

        // Set paper size and orientation
        $pdf->setPaper('A4', 'landscape');

        // Download the PDF
        return $pdf->download('securities-report-' . now()->format('Y-m-d') . '.pdf');
    }

    // sorted by status
    /**
     * Scope a query to order securities by status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByStatus($query)
    {
        return $query->orderByRaw("FIELD(status, 'expired', 'active', 'released')")
            ->orderBy('id', 'desc');
    }
}
