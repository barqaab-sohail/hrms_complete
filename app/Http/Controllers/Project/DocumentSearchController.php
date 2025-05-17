<?php

namespace App\Http\Controllers\Project;

use Illuminate\Http\Request;
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

        if ($request->has('q') || $request->hasAny(['reference_no', 'description', 'date_from', 'date_to', 'pr_folder_name_id'])) {
            $documents = $this->searchService->advancedSearch($request->all());
        } else {
            $documents = PrDocument::with(['prDocumentContent', 'prFolderName'])
                ->orderBy('document_date', 'desc')
                ->paginate(15);
        }

        return view('project.search.content_search', [
            'documents' => $documents,
            'folders' => $folders
        ]);
    }

    public function download($id)
    {
        $document = PrDocument::findOrFail($id);
        $filePath = storage_path('app/public/' . $document->path . $document->file_name);

        return response()->download($filePath, $document->file_name);
    }
}
