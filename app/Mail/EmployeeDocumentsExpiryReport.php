<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmployeeDocumentsExpiryReport extends Mailable
{
    use Queueable, SerializesModels;

    public $reportData;

    public function __construct($reportData)
    {
        $this->reportData = $reportData;
    }

    public function build()
    {
        return $this->subject('Weekly Employee Documents Expiry Report - ' . $this->reportData['reportDate'])
            ->view('emails.employee_documents_expiry_report');
    }
}
