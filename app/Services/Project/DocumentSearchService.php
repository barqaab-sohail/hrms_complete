<?php

namespace App\Services\Project;

use App\Models\Project\PrDocument;

class DocumentSearchService
{
    public function search($searchTerm, $perPage = 15)
    {
        return PrDocument::where(function ($query) use ($searchTerm) {
            // Search in document fields
            $query->where('reference_no', 'like', '%' . $searchTerm . '%')
                ->orWhere('description', 'like', '%' . $searchTerm . '%');
            //->orWhere('file_name', 'like', '%' . $searchTerm . '%');
        })
            ->orWhereHas('prDocumentContent', function ($query) use ($searchTerm) {
                // Search in content
                $query->where('content', 'like', '%' . $searchTerm . '%');
            })
            ->with(['prDocumentContent', 'prFolderName','prDetail']) // Eager load relationships
            ->orderBy('document_date', 'desc')
            ->paginate($perPage);
    }

    public function advancedSearch($params, $perPage = 15)
    {
        $query = PrDocument::with(['prDocumentContent', 'prFolderName','prDetail']);
        
        // Content search
        if (!empty($params['q'])) {
            $query->where(function($q) use ($params) {
                $q->where('reference_no', 'like', '%'.$params['q'].'%')
                  ->orWhere('description', 'like', '%'.$params['q'].'%')
                  ->orWhere('file_name', 'like', '%'.$params['q'].'%')
                  ->orWhereHas('prDocumentContent', function($q) use ($params) {
                      $q->where('content', 'like', '%'.$params['q'].'%');
                  });
            });
        }

        // Additional filters
        if (!empty($params['reference_no'])) {
            $query->where('reference_no', 'like', '%'.$params['reference_no'].'%');
        }
        
        if (!empty($params['description'])) {
            $query->where('description', 'like', '%'.$params['description'].'%');
        }
        
        // Date range
        if (!empty($params['date_from'])) {
            $query->where('document_date', '>=', $params['date_from']);
        }
        
        if (!empty($params['date_to'])) {
            $query->where('document_date', '<=', $params['date_to']);
        }
        
        // Folder filter
        if (!empty($params['pr_folder_name_id'])) {
            $query->where('pr_folder_name_id', $params['pr_folder_name_id']);
        }

         // Add project name filter
         if (!empty($params['project_name'])) {
            $query->where('pr_detail_id', $params['project_name']);
        }
        
        return $query->orderBy('document_date', 'desc')
                    ->paginate($perPage);
    }
}
