<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentSearchController extends Controller
{
    // List of available tables with their field mappings
    private $tableConfigs = [
        'as_documentations' => [
            'description_field' => 'description',
            'document_identification_id' => 'asset_id',
            'date_field' => 'created_at',
            'size_field' => 'size',
            'file_field' => 'file_name',
            'path_field' => 'path'
        ],
        'hr_documentations' => [
            'description_field' => 'description',
            'document_identification_id' => 'hr_employee_id',
            'date_field' => 'document_date',
            'size_field' => 'size',
            'file_field' => 'file_name',
            'path_field' => 'path'
        ],
        'pr_documents' => [
            'description_field' => 'description',
            'document_identification_id' => 'pr_detail_id',
            'date_field' => 'document_date',
            'size_field' => 'size',
            'file_field' => 'file_name',
            'path_field' => 'path'
        ],
        'sub_documents' => [
            'description_field' => 'description',
            'document_identification_id' => 'submission_id',
            'date_field' => 'created_at',
            'size_field' => 'size',
            'file_field' => 'file_name',
            'path_field' => 'path'
        ],
        'admin_documents' => [
            'description_field' => 'description',
            'document_identification_id' => 'id',
            'date_field' => 'document_date',
            'size_field' => 'size',
            'file_field' => 'file_name',
            'path_field' => 'path'
        ],
        'folder_documents' => [
            'description_field' => 'description',
            'document_identification_id' => 'folder_id',
            'date_field' => 'document_date',
            'size_field' => 'size',
            'file_field' => 'file_name',
            'path_field' => 'path'
        ],
        'personal_documents' => [
            'description_field' => 'description',
            'document_identification_id' => 'user_id',
            'date_field' => 'document_date',
            'size_field' => 'size',
            'file_field' => 'file_name',
            'path_field' => 'path'
        ],
    ];

    /**
     * Display the search form
     */
    public function index()
    {
        // Get basic statistics for each table
        $tableStats = $this->getTableStatistics();
        
        return view('admin.documentSizeSearch.index', compact('tableStats'));
    }

    /**
     * Search for large files
     */
    public function search(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'table_name' => 'required|string|in:' . implode(',', array_keys($this->tableConfigs)),
            'limit' => 'required|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $tableName = $request->table_name;
        $limit = $request->limit;
        
        // Get table configuration
        $config = $this->tableConfigs[$tableName];
        
        try {
            // Build query to get largest files - CAST size to integer for proper sorting
            $files = DB::table($tableName)
            ->select([
                'id',
                $config['description_field'] . ' as description',
                $config['document_identification_id'] . ' as document_identification_id', // ADD THIS
                $config['date_field'] . ' as document_date',
                $config['size_field'] . ' as size',
                DB::raw('CAST(' . $config['size_field'] . ' AS UNSIGNED) as size_numeric'),
                $config['file_field'] . ' as file_name',
                $config['path_field'] . ' as file_path',
                DB::raw("'" . $tableName . "' as source_table")
            ])
            ->whereNotNull($config['size_field'])
            ->whereRaw($config['size_field'] . ' REGEXP \'^[0-9]+$\'')
            ->orderBy('size_numeric', 'desc')
            ->limit($limit)
            ->get();

            // Format the results
            $formattedFiles = $files->map(function ($file) use ($tableName, $config) {
                $sizeNumeric = $file->size_numeric ?: intval($file->size);
                
                // Get document_identification_id
                $docId = $file->document_identification_id ?? null;
                
                // Generate edit URL based on table
                $editUrl = null;
                if ($docId) {
                    $editUrl = $this->getEditUrl($tableName, $docId);
                }
                
                return [
                    'id' => $file->id,
                    'description' => $file->description,
                    'document_date' => $this->formatDate($file->document_date),
                    'size' => $this->formatBytes($sizeNumeric),
                    'size_in_bytes' => $sizeNumeric,
                    'file_name' => $file->file_name,
                    'file_path' => $file->file_path,
                    'source_table' => $file->source_table,
                    'document_identification_id' => $docId,
                    'download_url' => route('document-search.download', [
                        'table' => $file->source_table, 
                        'id' => $file->id
                    ]),
                    'edit_url' => $editUrl
                ];
            });

            // Calculate total size using numeric values
            $totalSize = $files->sum('size_numeric');
            
            // Get table statistics for comparison - using numeric conversion
            $tableStats = DB::table($tableName)
                ->select([
                    DB::raw('COUNT(*) as total_files'),
                    DB::raw('SUM(CAST(' . $config['size_field'] . ' AS UNSIGNED)) as total_size'),
                    DB::raw('AVG(CAST(' . $config['size_field'] . ' AS UNSIGNED)) as avg_size'),
                    DB::raw('MAX(CAST(' . $config['size_field'] . ' AS UNSIGNED)) as max_size'),
                    DB::raw('COUNT(CASE WHEN ' . $config['size_field'] . ' REGEXP \'^[0-9]+$\' THEN 1 END) as numeric_files')
                ])
                ->first();

            return response()->json([
                'success' => true,
                'message' => 'Found ' . $files->count() . ' largest files',
                'data' => [
                    'files' => $formattedFiles,
                    'summary' => [
                        'total_files_found' => $files->count(),
                        'total_size_found' => $this->formatBytes($totalSize),
                        'largest_file' => $files->count() > 0 ? $this->formatBytes($files->max('size_numeric')) : '0 B',
                        'table_statistics' => [
                            'total_files_in_table' => $tableStats->total_files ?? 0,
                            'numeric_files_in_table' => $tableStats->numeric_files ?? 0,
                            'total_size_in_table' => $this->formatBytes($tableStats->total_size ?? 0),
                            'average_file_size' => $this->formatBytes($tableStats->avg_size ?? 0),
                            'maximum_file_size' => $this->formatBytes($tableStats->max_size ?? 0)
                        ]
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Document search error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error searching table: ' . $e->getMessage()
            ], 500);
        }
    }

    // Add this method to the controller class:
    /**
     * Get edit URL based on table type
     */
    private function getEditUrl($tableName, $documentId)
    {
        $baseUrl = url('/');
        
        switch ($tableName) {
            case 'as_documentations':
                return $baseUrl . '/hrms/asset/' . $documentId . '/edit';
            
            case 'hr_documentations':
                return $baseUrl . '/hrms/employee/' . $documentId . '/edit';
            
            case 'pr_documents':
                return $baseUrl . '/hrms/project/project/' . $documentId . '/edit';
            
            case 'sub_documents':
                return $baseUrl . '/hrms/submission/' . $documentId . '/edit';
            
           
            // Add other tables as needed
            default:
                return null;
        }
    }

    /**
     * Download a specific file
     */
    public function download(Request $request, $table, $id)
    {
        try {
            // Validate table name
            if (!array_key_exists($table, $this->tableConfigs)) {
                abort(404, 'Invalid table specified');
            }

            // Get file record
            $config = $this->tableConfigs[$table];
            
            $file = DB::table($table)
                ->where('id', $id)
                ->select([
                    'id',
                    $config['file_field'] . ' as file_name',
                    $config['path_field'] . ' as file_path',
                    $config['description_field'] . ' as description'
                ])
                ->first();

            if (!$file) {
                abort(404, 'File not found');
            }

            // Build full file path
            $filePath = $file->file_path . '/' . $file->file_name;
            
            // Check if file exists in storage
            if (!Storage::disk('public')->exists($filePath)) {
                // Try without leading slash
                $filePath = ltrim($filePath, '/');
                
                if (!Storage::disk('public')->exists($filePath)) {
                    // Try with storage path
                    $storagePath = 'storage/' . $filePath;
                    
                    if (!file_exists(public_path($storagePath))) {
                        abort(404, 'File not found in storage. Path: ' . $filePath);
                    }
                    
                    // Serve from public storage
                    return response()->download(
                        public_path($storagePath), 
                        $file->file_name,
                        [
                            'Content-Type' => $this->getMimeType($file->file_name),
                            'Content-Disposition' => 'attachment; filename="' . $file->file_name . '"'
                        ]
                    );
                }
            }

            // Serve from Laravel storage
            return Storage::disk('public')->download($filePath, $file->file_name);

        } catch (\Exception $e) {
            \Log::error('File download error: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Download error: ' . $e->getMessage()
                ], 500);
            }
            
            abort(500, 'Error downloading file: ' . $e->getMessage());
        }
    }

    /**
     * Bulk download multiple files as ZIP
     */
    public function bulkDownload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'table' => 'required|string|in:' . implode(',', array_keys($this->tableConfigs)),
            'ids' => 'required|array',
            'ids.*' => 'integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $table = $request->table;
        $ids = $request->ids;
        
        try {
            // Get file records
            $config = $this->tableConfigs[$table];
            
            $files = DB::table($table)
                ->whereIn('id', $ids)
                ->select([
                    'id',
                    $config['file_field'] . ' as file_name',
                    $config['path_field'] . ' as file_path',
                    $config['description_field'] . ' as description'
                ])
                ->get();

            if ($files->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No files found for download'
                ], 404);
            }

            // Create ZIP file
            $zipFileName = 'large_files_' . $table . '_' . now()->format('Y-m-d_H-i-s') . '.zip';
            $zipPath = storage_path('app/temp/' . $zipFileName);
            
            // Ensure temp directory exists
            if (!is_dir(dirname($zipPath))) {
                mkdir(dirname($zipPath), 0755, true);
            }

            $zip = new \ZipArchive();
            if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
                $addedFiles = 0;
                
                foreach ($files as $file) {
                    $filePath = $file->file_path . '/' . $file->file_name;
                    
                    // Try to find the file
                    $fullPath = null;
                    
                    if (Storage::disk('public')->exists($filePath)) {
                        $fullPath = Storage::disk('public')->path($filePath);
                    } else {
                        $filePath = ltrim($filePath, '/');
                        
                        if (Storage::disk('public')->exists($filePath)) {
                            $fullPath = Storage::disk('public')->path($filePath);
                        } else {
                            $storagePath = public_path('storage/' . $filePath);
                            
                            if (file_exists($storagePath)) {
                                $fullPath = $storagePath;
                            }
                        }
                    }
                    
                    if ($fullPath && file_exists($fullPath)) {
                        // Add file to zip with a clean name
                        $cleanName = $this->cleanFileName($file->file_name);
                        $zip->addFile($fullPath, $cleanName);
                        $addedFiles++;
                    }
                }
                
                $zip->close();
                
                if ($addedFiles === 0) {
                    unlink($zipPath);
                    return response()->json([
                        'success' => false,
                        'message' => 'None of the requested files could be found'
                    ], 404);
                }
                
                // Return download response
                return response()->download(
                    $zipPath,
                    $zipFileName,
                    [
                        'Content-Type' => 'application/zip',
                        'Content-Disposition' => 'attachment; filename="' . $zipFileName . '"'
                    ]
                )->deleteFileAfterSend(true);
                
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create ZIP archive'
                ], 500);
            }

        } catch (\Exception $e) {
            \Log::error('Bulk download error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error during bulk download: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get statistics for all tables
     */
    private function getTableStatistics()
    {
        $stats = [];
        
        foreach ($this->tableConfigs as $tableName => $config) {
            try {
                $result = DB::table($tableName)
                    ->select([
                        DB::raw('COUNT(*) as file_count'),
                        DB::raw('COALESCE(SUM(CAST(' . $config['size_field'] . ' AS UNSIGNED)), 0) as total_size'),
                        DB::raw('COALESCE(MAX(CAST(' . $config['size_field'] . ' AS UNSIGNED)), 0) as max_size'),
                        DB::raw('COALESCE(AVG(CAST(' . $config['size_field'] . ' AS UNSIGNED)), 0) as avg_size'),
                        DB::raw('COUNT(CASE WHEN ' . $config['size_field'] . ' REGEXP \'^[0-9]+$\' THEN 1 END) as numeric_count')
                    ])
                    ->first();

                $stats[$tableName] = [
                    'file_count' => $result->file_count ?? 0,
                    'total_size' => $this->formatBytes($result->total_size ?? 0),
                    'total_size_bytes' => $result->total_size ?? 0,
                    'max_size' => $this->formatBytes($result->max_size ?? 0),
                    'avg_size' => $this->formatBytes($result->avg_size ?? 0),
                    'numeric_count' => $result->numeric_count ?? 0,
                    'human_name' => ucwords(str_replace('_', ' ', $tableName))
                ];
                
                // Add warning if not all files have numeric sizes
                if ($result->file_count > 0 && $result->numeric_count < $result->file_count) {
                    $stats[$tableName]['warning'] = ($result->file_count - $result->numeric_count) . ' files have non-numeric sizes';
                }
                
            } catch (\Exception $e) {
                // If table doesn't exist or has issues, mark as unavailable
                $stats[$tableName] = [
                    'file_count' => 0,
                    'total_size' => 'N/A',
                    'total_size_bytes' => 0,
                    'max_size' => 'N/A',
                    'avg_size' => 'N/A',
                    'numeric_count' => 0,
                    'human_name' => ucwords(str_replace('_', ' ', $tableName)),
                    'error' => $e->getMessage()
                ];
            }
        }
        
        return $stats;
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        // Handle string input
        if (is_string($bytes)) {
            $bytes = intval($bytes);
        }
        
        if ($bytes <= 0) return '0 B';
        
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Format date
     */
    private function formatDate($date)
    {
        if (!$date) return 'N/A';
        
        try {
            return \Carbon\Carbon::parse($date)->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            return $date;
        }
    }

    /**
     * Get MIME type based on file extension
     */
    private function getMimeType($filename)
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        $mimeTypes = [
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'txt' => 'text/plain',
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
        ];
        
        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }

    /**
     * Clean file name for ZIP archive
     */
    private function cleanFileName($filename)
    {
        // Remove special characters, keep only alphanumeric, dots, dashes, and underscores
        $clean = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
        
        // Remove multiple underscores
        $clean = preg_replace('/_+/', '_', $clean);
        
        return $clean;
    }
}