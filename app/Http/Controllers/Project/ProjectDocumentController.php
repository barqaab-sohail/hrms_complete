<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Requests\Project\DocumentStore;
use App\Models\Project\PrFolderName;
use App\Models\Project\PrDocument;
use App\Models\Project\PrDocumentContent;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrDocumentation;
use App\Models\Hr\HrDocumentationProject;
use App\Helper\DocxConversion;
use DataTables;
use DB;
use Illuminate\Support\Facades\Cache;

class ProjectDocumentController extends Controller
{
    // Cache duration in minutes
    const CACHE_DURATION = 60;

    public function documentDataTable($data)
    {
        // Pre-load short URLs to avoid N+1 queries
        $documentsWithShortUrls = $data->map(function ($document) {
            // This will use the cached version from the model
            $document->tiny_url; // Force generation to cache it
            return $document;
        });

        return DataTables::of($documentsWithShortUrls)
            ->addIndexColumn()
            ->addColumn('document', function ($row) {
                if ($row->extension != 'pdf') {
                    return '<img id="ViewIMG" src="' . $row->full_path . '" href="' . $row->full_path . '" width="30/" style="cursor: pointer;">';
                } else {
                    return '<img id="ViewPDF" src="https://hrms.barqaab.pk/Massets/images/document.png" href="' . $row->full_path . '" width="30/" style="cursor: pointer;">';
                }
            })
            ->editColumn('description', function ($row) {
                $shortDescription = strlen($row->description) > 50
                    ? substr($row->description, 0, 50) . '...'
                    : $row->description;

                return '<span title="' . htmlspecialchars($row->description, ENT_QUOTES) . '">'
                    . htmlspecialchars($shortDescription)
                    . '</span>';
            })
            ->addColumn('copy_link', function ($row) {
                return '<a class="copyLink" link="' . $row->tiny_url . '" style="cursor: auto;" title="Click for Copy Tiny URL"><img src="https://hrms.barqaab.pk/Massets/images/copyLink.png" width="30"></a>';
            })
            ->addColumn('Edit', function ($row) {
                $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editDocument">Edit</a>';
                return $btn;
            })
            ->addColumn('Delete', function ($row) {
                $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteDocument">Delete</a>';
                return $btn;
            })
            ->rawColumns(['document', 'description', 'copy_link', 'Edit', 'Delete'])
            ->make(true);
    }

    public function showFolder($folderId, $prDetailId, Request $request)
    {
        $cacheKey = "pr_folder_{$folderId}_documents_{$prDetailId}_" . md5(serialize($request->all()));

        $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($folderId, $prDetailId, $request) {
            $query = PrDocument::with(['prDocumentContent', 'prFolderName']) // Eager load relationships
                ->where('pr_detail_id', $prDetailId)
                ->where('pr_folder_name_id', $folderId);

            // Apply search filters if provided
            $this->applySearchFilters($query, $request);

            return $query->orderByRaw('ISNULL(document_date), document_date desc')
                ->take(500) // Limit to prevent memory issues
                ->get();
        });

        return $this->documentDataTable($data);
    }

    public function show(Request $request, $id)
    {
        // Cache folder names to avoid repeated queries
        $prFolderNames = Cache::remember('pr_folder_names', self::CACHE_DURATION * 24, function () {
            return PrFolderName::all();
        });

        if ($request->ajax()) {
            $view = view('project.document.create', compact('prFolderNames', 'id'))->render();
            return response()->json($view);
        } else {
            return back()->withError('Please contact to administrator, SSE_JS');
        }
    }

    public function create(Request $request)
    {
        if ($request->ajax()) {
            $cacheKey = "pr_documents_{$request->prDetailId}_" . md5(serialize($request->all()));

            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($request) {
                $query = PrDocument::with(['prDocumentContent', 'prFolderName']) // Eager load relationships
                    ->where('pr_detail_id', $request->prDetailId);

                // Apply search filters if provided
                $this->applySearchFilters($query, $request);

                return $query->orderByRaw('ISNULL(document_date), document_date desc')
                    ->take(500) // Limit to prevent memory issues
                    ->get();
            });

            return $this->documentDataTable($data);
        }
    }

    private function applySearchFilters($query, $request)
    {
        if ($request->has('search_reference') && !empty($request->search_reference)) {
            $query->where('reference_no', 'like', '%' . $request->search_reference . '%');
        }

        if ($request->has('search_description') && !empty($request->search_description)) {
            $query->where('description', 'like', '%' . $request->search_description . '%');
        }

        // Replace the date handling section with this:
        if ($request->has('search_date') && !empty($request->search_date)) {
            $date = trim($request->search_date);

            // Check if the date is already in YYYY-MM-DD format
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                $query->where('document_date', $date);
            } else {
                // Try to parse the date using Carbon
                try {
                    $parsedDate = \Carbon\Carbon::createFromFormat('d/m/Y', $date);
                    if ($parsedDate) {
                        $query->where('document_date', $parsedDate->format('Y-m-d'));
                    }
                } catch (\Exception $e) {
                    // If parsing with d/m/Y format fails, try other formats
                    try {
                        $parsedDate = \Carbon\Carbon::parse($date);
                        $query->where('document_date', $parsedDate->format('Y-m-d'));
                    } catch (\Exception $e) {
                        // Final fallback: search as string
                        $query->where('document_date', 'like', '%' . $date . '%');
                    }
                }
            }
        }

        if ($request->has('search_content') && !empty($request->search_content)) {
            $query->whereHas('prDocumentContent', function ($q) use ($request) {
                $q->where('content', 'like', '%' . $request->search_content . '%');
            });
        }
    }

    public function reference(Request $request)
    {
        if ($request->get('query')) {
            $query = $request->get('query');

            // Use caching for reference lookup
            $cacheKey = "pr_reference_{$query}_" . session('pr_detail_id');

            $data = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($query) {
                return DB::table('pr_documents')
                    ->where('pr_detail_id', session('pr_detail_id'))
                    ->where('reference_no', 'LIKE', "%{$query}%")
                    ->take(3)
                    ->get(['reference_no']);
            });

            $output = '<ul style="display:block; position:relative;list-style-type:none;">';
            foreach ($data as $row) {
                $output .= '<li style="color: red;">' . $row->reference_no . '</li>';
            }
            $output .= '</ul>';

            echo $output;
        }
    }

    public function store(DocumentStore $request)
    {
        $input = $request->only('pr_folder_name_id', 'reference_no', 'description', 'document', 'pr_document_id', 'pr_detail_id');

        if ($request->filled('document_date')) {
            $input['document_date'] = \Carbon\Carbon::parse($request->document_date)->format('Y-m-d');
        }

        DB::transaction(function () use ($request, $input) {
            // Clear relevant caches
            $this->clearDocumentCaches($request->pr_detail_id);

            // Only document Available
            if ($request->hasFile('document')) {
                $extension = request()->document->getClientOriginalExtension();

                $fileName = strtolower(preg_replace('/[^a-zA-Z0-9_ -]/s', '', str_replace(" ", "_", $input['description']))) . '-' . time() . '.' . $extension;
                $folderName = "project/" . $request->pr_detail_id . "/";

                //store file
                $request->file('document')->storeAs('public/' . $folderName, $fileName);

                $file_path = storage_path('app/public/' . $folderName . $fileName);

                $input['content'] = '';

                if (($extension == 'doc') || ($extension == 'docx')) {
                    $text = new DocxConversion($file_path);
                    $input['content'] = mb_strtolower($text->convertToText());
                } else if ($extension == 'pdf') {
                    $reader = new \Asika\Pdf2text;
                    $input['content'] = mb_strtolower($reader->decode($file_path));
                }

                $input['file_name'] = $fileName;
                $input['size'] = $request->file('document')->getSize();
                $input['path'] = $folderName;
                $input['extension'] = $extension;
                $input['pr_detail_id'] = $request->pr_detail_id;

                //check create new data or update data
                if ($request->pr_document_id) {
                    //update record
                    $prDocument = PrDocument::findOrFail($request->pr_document_id);
                    $path = public_path('storage/' . $prDocument->path . $prDocument->file_name);
                    if (File::exists($path)) {
                        File::delete($path);
                    }
                    $prDocument->update($input);
                    $input['pr_document_id'] = $request->pr_document_id;
                } else {
                    //create record
                    $prDocument = PrDocument::create($input);
                    $input['pr_document_id'] = $prDocument->id;
                }

                if (strlen($input['content']) > 50 && strlen($input['content']) < 16777200) {
                    PrDocumentContent::updateOrCreate(
                        ['pr_document_id' => $input['pr_document_id']],
                        $input
                    );
                }
            } else {
                $prDocument = PrDocument::findOrFail($request->pr_document_id);
                $prDocument->update($input);
            }
        });  //end transaction

        return response()->json(['status' => 'OK', 'message' => "Document Successfully Saved"]);
    }

    public function edit($id)
    {
        // Eager load related data to avoid N+1 queries
        $prDocument = PrDocument::with('prDocumentContent')->find($id);
        return response()->json($prDocument);
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $prDocument = PrDocument::findOrFail($id);

            // Clear relevant caches before deletion
            $this->clearDocumentCaches($prDocument->pr_detail_id);

            $path = public_path('storage/' . $prDocument->path . $prDocument->file_name);

            // Use direct deletion instead of multiple queries
            HrDocumentation::whereIn('id', function ($query) use ($id) {
                $query->select('hr_documentation_id')
                    ->from('hr_documentation_projects')
                    ->where('pr_document_id', $id);
            })->delete();

            $prDocument->forceDelete();

            if (File::exists($path)) {
                File::delete($path);
            }
        });  //end transaction

        return response()->json(['status' => 'OK', 'message' => "Data Successfully Deleted"]);
    }

    public function refreshTable()
    {
        // Use caching for document list
        $cacheKey = 'pr_documents_' . session('pr_detail_id');

        $documentIds = Cache::remember($cacheKey, self::CACHE_DURATION, function () {
            return PrDocument::where('pr_detail_id', session('pr_detail_id'))->get();
        });

        return view('project.document.list', compact('documentIds'));
    }

    /**
     * Clear all document-related caches for a specific project
     */
    private function clearDocumentCaches($prDetailId)
    {
        $cacheKeys = [
            'pr_folder_names',
            "pr_documents_{$prDetailId}",
            "pr_reference_*_{$prDetailId}"
        ];

        foreach ($cacheKeys as $key) {
            if (strpos($key, '*') !== false) {
                // Clear all cache for this project (simplified approach)
                Cache::flush();
                break;
            } else {
                Cache::forget($key);
            }
        }

        // Also clear short URL caches
        Cache::flush();
    }
}
