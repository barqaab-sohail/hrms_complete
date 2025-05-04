<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MissingDocumentsReport extends Mailable
{
    use Queueable, SerializesModels;

    public $missingDocuments;
    public $reportDate;

    public function __construct($missingDocuments)
    {
        $this->missingDocuments = $missingDocuments;
        $this->reportDate = now()->format('M d, Y');
    }

    public function build()
    {
        return $this->subject('Weekly Missing Documents Report - ' . $this->reportDate)
            ->view('emails.missing_documents_report');
    }
}
