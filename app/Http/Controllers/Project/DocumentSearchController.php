<?php

namespace App\Http\Controllers\Project;

use Illuminate\Http\Request;
use App\Models\Project\PrDetail;
use App\Models\Project\PrDocument;
use App\Http\Controllers\Controller;
use App\Models\Project\PrFolderName;
use App\Services\Project\DocumentSearchService;

class DocumentSearchController extends Controller
{
    protected $searchService;

    public function __construct(DocumentSearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:3',
        ]);

        $results = $this->searchService->search($request->q);

        return response()->json([
            'data' => $results,
            'message' => 'Search results retrieved successfully'
        ]);
    }

    public function advancedSearch(Request $request)
    {
        $results = $this->searchService->advancedSearch($request->all());

        return response()->json([
            'data' => $results,
            'message' => 'Advanced search results retrieved successfully'
        ]);
    }


  


    public function index(Request $request)
{
    $folders = PrFolderName::orderBy('name')->get();
    $projects = PrDetail::orderBy('name')->get(); // Add this line
    // Get search results or all documents
    $documents = $request->hasAny(['q', 'reference_no', 'description', 'date_from', 'date_to', 'pr_folder_name_id'])
        ? $this->searchService->advancedSearch($request->all())
        : PrDocument::with(['prDocumentContent', 'prFolderName','prDetail'])
                   ->orderBy('document_date', 'desc')
                   ->paginate(15);

    // Return JSON for API requests
    if ($request->wantsJson()) {
        return response()->json([
            'documents' => $documents,
            'folders' => $folders,
            'projects' => $projects, // Add this line
            'searchParams' => $request->all()
        ]);
    }

    // Return Blade view for web requests
    return view('project.search.content_search', [
        'documents' => $documents,
        'folders' => $folders,
        'projects' => $projects, // Add this line
        'searchParams' => $request->all()
    ]);
}

    public function download($id)
    {
        $document = PrDocument::findOrFail($id);
        $filePath = storage_path('app/public/' . $document->path . $document->file_name);

        return response()->download($filePath, $document->file_name);
    }
}
