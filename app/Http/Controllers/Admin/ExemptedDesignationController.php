<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExemptedDesignationController extends Controller
{
    private $filePath = 'exempted_designations.txt';

    public function index()
    {
        $designations = $this->readDesignationsFromFile();
        return view('admin.exempted-designations.index', compact('designations'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'designations' => 'required|array',
            'designations.*' => 'required|string'
        ]);

        $designations = $request->designations;
        
        // Remove any empty designations
        $designations = array_filter($designations, function($designation) {
            return !empty(trim($designation));
        });
        
        // Format the designations for file storage
        $content = implode(",\n", array_map(function($designation) {
            return '"' . trim($designation) . '"';
        }, $designations));

        // Write to file
        if (Storage::put($this->filePath, $content)) {
            return redirect()->back()->with('success', 'Designations updated successfully!');
        }

        return redirect()->back()->with('error', 'Failed to update designations.');
    }

    public function readDesignationsFromFile()
    {
        if (!Storage::exists($this->filePath)) {
            // Return default designations if file doesn't exist
            return [
                "kitchen helper",
                "security guard",
                "office helper",
                "utility person",
                "record keeper",
                "driver",
                "electrician",
                "sanitary worker",
                "sanitary worker part time",
                "cook",
                "naib qasid",
                "chowkidarwatchman",
                "line foreman",
                "patwari",
                "khalasi",
                "sweeper sanitary worker",
                "driver cum utility person part time",
                "part time gardner",
                "sweeper",
                "office boy cum mali",
                "recovery officer",
                "utility person part time",
                "field helper",
                "sweeper (part time)",
                "chakbandi coordinator",
                "hastel attended",
                "sanitary worker part time",
                "utility person cook",
                "naib qasid sanitary worker",
                "sweeper (part time)",
                "part time helper"
            ];
        }

        $content = Storage::get($this->filePath);
        
        // Process content into an array
        return array_map(function($line) {
            // Remove quotes, commas, and trim whitespace
            return trim(str_replace(['"', ',', "'"], '', $line));
        }, array_filter(explode("\n", $content), function($line) {
            // Filter out empty lines
            return !empty(trim($line));
        }));
    }

    public function examptEducationDocuments($designation)
    {
        // Normalize the designation by trimming and converting to lowercase for case-insensitive comparison
        $normalizedDesignation = strtolower(trim($designation));
        
        $examptedDesignations = $this->readDesignationsFromFile();
        
        // Convert all designations to lowercase for comparison
        $examptedDesignations = array_map('strtolower', $examptedDesignations);
        
        // Check if the normalized designation exists in the exempted list
        return in_array($normalizedDesignation, $examptedDesignations, true);
    }
}