<?php

namespace App\Http\Controllers\Self;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class FileCompressionController extends Controller
{
    public function index()
    {
        return view('self.reduce_file_size.file-compression');
    }

public function compress(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,bmp,webp|max:40960',
            'quality' => 'sometimes|integer|min:1|max:100',
            'target_size_kb' => 'sometimes|integer|min:10|max:10240'
        ]);

        try {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $extension = strtolower($file->getClientOriginalExtension());
            $quality = $request->quality ?? 75;
            $targetSizeKb = $request->target_size_kb;

            // Generate unique filename
            $filename = 'compressed_' . time() . '_' . Str::random(10) . '.' . $extension;
            $tempPath = storage_path('app/temp/' . $filename);

            // Create temp directory if it doesn't exist
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            $originalSize = $file->getSize();
            $compressedSize = 0;

            // Compress image
            if ($targetSizeKb) {
                // Use target size mode - improved algorithm
                $targetSizeBytes = $targetSizeKb * 1024;
                $compressedSize = $this->compressToExactSize($file->getRealPath(), $tempPath, $extension, $targetSizeBytes);
            } else {
                // Use quality mode
                $compressedSize = $this->compressImage($file->getRealPath(), $tempPath, $extension, $quality);
            }

            // Check if compression was successful
            if ($compressedSize === 0) {
                throw new \Exception('Compression failed');
            }

            // Verify size for target mode
            if ($targetSizeKb) {
                $actualSizeKb = round($compressedSize / 1024);
                $targetSizeBytes = $targetSizeKb * 1024;
                
                // Allow 5% tolerance
                if (abs($compressedSize - $targetSizeBytes) > $targetSizeBytes * 0.05) {
                    Log::warning("Size target not met. Target: {$targetSizeKb}KB, Actual: {$actualSizeKb}KB");
                }
            }

            return response()
                ->download($tempPath, 'compressed_' . $originalName)
                ->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Compression failed: ' . $e->getMessage())
                ->withInput();
        }
    }
    /**
     * Estimate output size for images
     */
     public function estimateSize(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,bmp,webp|max:40960',
            'quality' => 'required|integer|min:1|max:100',
            'target_size_kb' => 'sometimes|integer|min:10|max:10240'
        ]);

        try {
            $file = $request->file('file');
            $extension = strtolower($file->getClientOriginalExtension());
            $quality = $request->quality;
            $targetSizeKb = $request->target_size_kb;

            $originalSize = $file->getSize();
            $estimatedSize = $originalSize;
            $estimatedQuality = $quality;

            if ($targetSizeKb) {
                $estimatedSize = $targetSizeKb * 1024;
                $estimatedQuality = $this->estimateQualityForTargetSize($file->getRealPath(), $extension, $estimatedSize);
            } else {
                $estimatedSize = $this->estimateImageSize($file->getRealPath(), $extension, $quality);
            }

            $reduction = round(($originalSize - $estimatedSize) / $originalSize * 100, 2);
            $reduction = max(0, $reduction);

            return response()->json([
                'success' => true,
                'original_size' => $originalSize,
                'estimated_size' => $estimatedSize,
                'estimated_quality' => $estimatedQuality,
                'reduction_percent' => $reduction,
                'original_size_formatted' => $this->formatBytes($originalSize),
                'estimated_size_formatted' => $this->formatBytes($estimatedSize),
                'reduction_formatted' => $reduction . '%',
                'estimated_quality_display' => $targetSizeKb ? 'Auto (' . $estimatedQuality . '%)' : $estimatedQuality . '%'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Estimation failed: ' . $e->getMessage()
            ]);
        }
    }

    private function compressToExactSize($sourcePath, $destinationPath, $extension, $targetSizeBytes)
    {
        $originalSize = filesize($sourcePath);
        
        // If target is larger than original, just copy with minimal compression
        if ($targetSizeBytes >= $originalSize) {
            return $this->compressImage($sourcePath, $destinationPath, $extension, 95);
        }

        $minQuality = 5;
        $maxQuality = 95;
        $bestQuality = 50;
        $bestSize = 0;
        $attempts = 0;
        $maxAttempts = 8;

        // Binary search for optimal quality
        while ($attempts < $maxAttempts && ($maxQuality - $minQuality) > 2) {
            $attempts++;
            $currentQuality = round(($minQuality + $maxQuality) / 2);
            
            $this->compressImage($sourcePath, $destinationPath, $extension, $currentQuality);
            $currentSize = filesize($destinationPath);

            if ($currentSize === 0) {
                break;
            }

            // Store the best result so far
            if (abs($currentSize - $targetSizeBytes) < abs($bestSize - $targetSizeBytes) || $bestSize === 0) {
                $bestQuality = $currentQuality;
                $bestSize = $currentSize;
            }

            if ($currentSize > $targetSizeBytes) {
                // Need lower quality
                $maxQuality = $currentQuality - 1;
            } else {
                // Need higher quality
                $minQuality = $currentQuality + 1;
            }
        }

        // Final compression with best quality found
        $this->compressImage($sourcePath, $destinationPath, $extension, $bestQuality);
        $finalSize = filesize($destinationPath);

        // If still not close enough, do one more adjustment
        if (abs($finalSize - $targetSizeBytes) > $targetSizeBytes * 0.1 && $attempts < $maxAttempts) {
            $adjustment = $finalSize > $targetSizeBytes ? -5 : 5;
            $adjustedQuality = max($minQuality, min($maxQuality, $bestQuality + $adjustment));
            $this->compressImage($sourcePath, $destinationPath, $extension, $adjustedQuality);
            $finalSize = filesize($destinationPath);
        }

        return $finalSize;
    }

    /**
     * Compress image to target file size
     */
    private function compressToTargetSize($sourcePath, $destinationPath, $extension, $targetSizeBytes)
    {
        $quality = 85; // Start with good quality
        $minQuality = 10;
        $maxQuality = 95;
        $attempts = 0;
        $maxAttempts = 10;

        do {
            $attempts++;
            
            // Compress with current quality
            $this->compressImage($sourcePath, $destinationPath, $extension, $quality);
            $currentSize = filesize($destinationPath);

            // Adjust quality based on result
            if ($currentSize > $targetSizeBytes * 1.1) { // 10% over target
                $quality = max($minQuality, $quality - 15);
            } elseif ($currentSize > $targetSizeBytes) {
                $quality = max($minQuality, $quality - 5);
            } elseif ($currentSize < $targetSizeBytes * 0.9) { // 10% under target
                $quality = min($maxQuality, $quality + 5);
            } else {
                break; // Close enough
            }

        } while ($attempts < $maxAttempts && abs($currentSize - $targetSizeBytes) > $targetSizeBytes * 0.1);

        return filesize($destinationPath);
    }

    /**
     * Estimate quality needed for target size
     */
    private function estimateQualityForTargetSize($sourcePath, $extension, $targetSizeBytes)
    {
        $originalSize = filesize($sourcePath);
        $ratio = $targetSizeBytes / $originalSize;
        
        // More accurate estimation based on file type
        $qualityFactors = [
            'jpg' => 100 * pow($ratio, 0.7),
            'jpeg' => 100 * pow($ratio, 0.7),
            'png' => 100 * (1 - (1 - $ratio) * 1.5),
            'gif' => 100 * $ratio,
            'bmp' => 100 * pow($ratio, 0.8),
            'webp' => 100 * pow($ratio, 0.6)
        ];

        $estimatedQuality = $qualityFactors[$extension] ?? (100 * $ratio);
        
        return max(10, min(95, round($estimatedQuality)));
    }

    /**
     * Improved image size estimation
     */
    private function estimateImageSize($filePath, $extension, $quality)
    {
        list($width, $height, $type) = getimagesize($filePath);
        $originalSize = filesize($filePath);
        
        // More accurate estimation based on empirical data
        $compressionRatios = [
            'jpg' => [
                'base' => 0.0003, // bytes per pixel at 100% quality
                'quality_factor' => 0.8
            ],
            'jpeg' => [
                'base' => 0.0003,
                'quality_factor' => 0.8
            ],
            'png' => [
                'base' => 0.001,
                'quality_factor' => 0.9
            ],
            'gif' => [
                'base' => 0.0005,
                'quality_factor' => 0.7
            ],
            'bmp' => [
                'base' => 0.003,
                'quality_factor' => 0.6
            ],
            'webp' => [
                'base' => 0.0002,
                'quality_factor' => 0.75
            ]
        ];

        $settings = $compressionRatios[$extension] ?? $compressionRatios['jpg'];
        
        $pixels = $width * $height;
        $qualityFactor = pow($quality / 100, $settings['quality_factor']);
        
        $estimatedSize = $pixels * $settings['base'] * $qualityFactor;
        
        // Ensure reasonable bounds
        $estimatedSize = max($originalSize * 0.1, min($originalSize, $estimatedSize));
        
        return round($estimatedSize);
    }

    /**
     * Compress image using native PHP functions
     */
    private function compressImage($sourcePath, $destinationPath, $extension, $quality)
    {
        list($width, $height, $type) = getimagesize($sourcePath);

        switch ($type) {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($sourcePath);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($sourcePath);
                // Preserve transparency for PNG
                imagesavealpha($image, true);
                break;
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($sourcePath);
                // Preserve transparency for GIF
                imagecolortransparent($image, imagecolorallocatealpha($image, 0, 0, 0, 127));
                break;
            case IMAGETYPE_BMP:
                $image = imagecreatefrombmp($sourcePath);
                break;
            case IMAGETYPE_WEBP:
                $image = imagecreatefromwebp($sourcePath);
                break;
            default:
                throw new \Exception('Unsupported image format');
        }

        if (!$image) {
            throw new \Exception('Failed to create image resource');
        }

        // Save compressed image with proper quality handling
        switch ($type) {
            case IMAGETYPE_JPEG:
                imagejpeg($image, $destinationPath, $quality);
                break;
            case IMAGETYPE_PNG:
                $pngQuality = 9 - round(($quality / 100) * 9);
                imagepng($image, $destinationPath, $pngQuality);
                break;
            case IMAGETYPE_GIF:
                imagegif($image, $destinationPath);
                break;
            case IMAGETYPE_BMP:
                imagebmp($image, $destinationPath);
                break;
            case IMAGETYPE_WEBP:
                imagewebp($image, $destinationPath, $quality);
                break;
        }

        imagedestroy($image);
        
        // Verify the file was created
        if (!file_exists($destinationPath)) {
            throw new \Exception('Compressed file was not created');
        }
        
        return filesize($destinationPath);
    }


    /**
     * Format bytes to human-readable format
     */
    private function formatBytes($bytes, $precision = 1)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    public function cleanup()
    {
        $files = glob(storage_path('app/temp/*'));
        $now = time();
        $deleted = 0;

        foreach ($files as $file) {
            if (is_file($file)) {
                if ($now - filemtime($file) >= 3600) {
                    unlink($file);
                    $deleted++;
                }
            }
        }

        return response()->json(['deleted' => $deleted]);
    }
}