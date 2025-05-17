<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;

class PrDocumentContent extends Model
{

    protected $fillable = ['pr_document_id', 'content'];

    public function prDocument()
    {
        return $this->belongsTo(PrDocument::class);
    }
}
