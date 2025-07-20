<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class JoiningDateMismatchExport implements FromView
{
    protected $data;

    public function __construct($misMatchDate)
    {
        $this->data = $misMatchDate;
    }

    public function view(): View
    {
        return view('exports.joining_date_mismatch', [
            'mismatches' => $this->data
        ]);
    }
}
