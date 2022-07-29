<?php

namespace App\Models\Project\Progress;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project\Progress\IssueStatus;

class PrIssue extends Model
{
    use HasFactory;

    public const RESOLVED = 'Resolved';
    public const PENDING = 'Pending';

    public static function isResolved()
    {
        return 'Nor working';
    }

    public function isPending()
    {
        return $this->status === IssueStatus::PENDING;
    }

}
