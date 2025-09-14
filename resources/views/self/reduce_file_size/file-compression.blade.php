@extends('layouts.master.master')
@section('title', 'Image Compression Tool')
@section('Heading')
<h3 class="text-themecolor">Image Compression Tool</h3>
@stop
@section('content')
     <style>
        .compression-card {
            max-width: 600px;
            margin: 2rem auto;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 1rem;
        }
        .drop-zone {
            border: 2px dashed #007bff;
            border-radius: 0.5rem;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .drop-zone:hover {
            border-color: #0056b3;
            background-color: #f8f9fa;
        }
        .drop-zone.dragover {
            border-color: #28a745;
            background-color: #e9f5e9;
        }
        .file-info {
            display: none;
            margin-top: 1rem;
        }
        .quality-slider {
            width: 100%;
        }
        .size-preview {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-radius: 0.5rem;
            padding: 1rem;
            margin: 1rem 0;
            border-left: 4px solid #2196f3;
        }
        .size-comparison {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 0.5rem 0;
        }
        .size-item {
            text-align: center;
            flex: 1;
        }
        .size-value {
            font-size: 1.1rem;
            font-weight: bold;
            color: #1976d2;
        }
        .size-label {
            font-size: 0.8rem;
            color: #666;
        }
        .reduction {
            text-align: center;
            margin-top: 0.5rem;
            padding: 0.5rem;
            background-color: #4caf50;
            color: white;
            border-radius: 1rem;
            font-weight: bold;
            font-size: 0.9rem;
        }
        .mode-switch {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        .mode-btn {
            flex: 1;
            padding: 0.5rem;
            border: 2px solid #dee2e6;
            border-radius: 0.5rem;
            background: white;
            cursor: pointer;
            text-align: center;
            transition: all 0.3s ease;
        }
        .mode-btn.active {
            border-color: #007bff;
            background-color: #007bff;
            color: white;
        }
        .target-size-input {
            display: none;
        }
    </style>
      <div class="container py-5">
        <div class="card compression-card">
            <div class="card-header bg-primary text-white">
                <h2 class="h4 mb-0">Image Compression Tool</h2>
                <small class="opacity-75">Compress images with precise size control</small>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('file.compress') }}" method="POST" enctype="multipart/form-data" id="compressionForm">
                    @csrf
                    
                    <div class="drop-zone mb-3" id="dropZone">
                        <i class="bi bi-image fs-1 text-primary"></i>
                        <p class="mt-2">Drag & drop your image here or click to browse</p>
                        <p class="text-muted small">Supported formats: JPG, JPEG, PNG, GIF, BMP, WEBP</p>
                        <p class="text-muted small">Max file size: 40MB</p>
                    </div>
                    
                    <input type="file" name="file" id="fileInput" class="d-none" accept=".jpg,.jpeg,.png,.gif,.bmp,.webp">
                    
                    <div class="file-info" id="fileInfo">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold">Selected image:</span>
                            <span id="fileName"></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold">File size:</span>
                            <span id="fileSize"></span>
                        </div>
                    </div>

                    <!-- Compression Mode Switch -->
                    <div class="mode-switch" id="modeSwitch" style="display: none;">
                        <div class="mode-btn active" data-mode="quality">
                            <i class="bi bi-sliders"></i> Quality Mode
                        </div>
                        <div class="mode-btn" data-mode="size">
                            <i class="bi bi-file-earmark-bar-graph"></i> Target Size Mode
                        </div>
                    </div>

                    <!-- Size Preview -->
                    <div class="size-preview" id="sizePreview" style="display: none;">
                        <h6 class="text-center mb-3">Size Estimation</h6>
                        <div class="size-comparison">
                            <div class="size-item">
                                <div class="size-value" id="originalSizeValue">0 KB</div>
                                <div class="size-label">Original</div>
                            </div>
                            <div class="size-item">
                                <i class="bi bi-arrow-right fs-4 text-muted"></i>
                            </div>
                            <div class="size-item">
                                <div class="size-value" id="estimatedSizeValue">0 KB</div>
                                <div class="size-label">Estimated</div>
                            </div>
                        </div>
                        <div class="reduction" id="reductionValue">
                            0% Reduction
                        </div>
                        <div class="text-center mt-2">
                            <small class="text-muted" id="estimationNote">
                                Estimated quality: <span id="estimatedQuality">75%</span>
                            </small>
                        </div>
                    </div>

                    <!-- Quality Mode -->
                    <div class="mb-3" id="qualitySettings">
                        <label for="quality" class="form-label">Image Quality (1-100)</label>
                        <input type="range" class="form-range quality-slider" id="quality" 
                               name="quality" min="1" max="100" value="75">
                        <div class="d-flex justify-content-between">
                            <small>Lower quality (smaller file)</small>
                            <span id="qualityValue">75%</span>
                            <small>Higher quality (larger file)</small>
                        </div>
                    </div>

                    <!-- Target Size Mode -->
                    <div class="mb-3 target-size-input" id="sizeSettings">
                        <label for="target_size_kb" class="form-label">Target File Size (KB)</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="target_size_kb" 
                                   name="target_size_kb" min="10" max="10240" value="100">
                            <span class="input-group-text">KB</span>
                        </div>
                        <small class="text-muted">Enter desired file size in kilobytes (10-10240 KB)</small>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-3" id="compressBtn" disabled>
                        <span id="buttonText">Compress and Download</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropZone = document.getElementById('dropZone');
            const fileInput = document.getElementById('fileInput');
            const fileInfo = document.getElementById('fileInfo');
            const fileName = document.getElementById('fileName');
            const fileSize = document.getElementById('fileSize');
            const compressBtn = document.getElementById('compressBtn');
            const qualitySlider = document.getElementById('quality');
            const qualityValue = document.getElementById('qualityValue');
            const targetSizeInput = document.getElementById('target_size_kb');
            const modeSwitch = document.getElementById('modeSwitch');
            const modeBtns = document.querySelectorAll('.mode-btn');
            const qualitySettings = document.getElementById('qualitySettings');
            const sizeSettings = document.getElementById('sizeSettings');
            const buttonText = document.getElementById('buttonText');
            const form = document.getElementById('compressionForm');
            const sizePreview = document.getElementById('sizePreview');
            const originalSizeValue = document.getElementById('originalSizeValue');
            const estimatedSizeValue = document.getElementById('estimatedSizeValue');
            const reductionValue = document.getElementById('reductionValue');
            const estimationNote = document.getElementById('estimationNote');
            const estimatedQuality = document.getElementById('estimatedQuality');

            let currentFile = null;
            let originalFileSize = 0;
            let isProcessing = false;
            let estimationTimeout = null;
            let currentMode = 'quality';
            let downloadStarted = false;

            function formatBytes(bytes, decimals = 1) {
                if (bytes === 0) return '0 KB';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return (bytes / Math.pow(k, i)).toFixed(decimals) + ' ' + sizes[i];
            }

            function updateSizeEstimation() {
                if (!currentFile) return;

                estimationNote.innerHTML = '<i class="bi bi-hourglass-split"></i> Calculating...';

                const formData = new FormData();
                formData.append('file', currentFile);
                
                if (currentMode === 'quality') {
                    formData.append('quality', qualitySlider.value);
                } else {
                    formData.append('target_size_kb', targetSizeInput.value);
                }

                fetch('{{ route("file.estimate.size") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        originalSizeValue.textContent = data.original_size_formatted;
                        estimatedSizeValue.textContent = data.estimated_size_formatted;
                        reductionValue.textContent = data.reduction_formatted + ' Reduction';
                        estimatedQuality.textContent = data.estimated_quality_display;
                        
                        // Color code based on reduction percentage
                        const reduction = data.reduction_percent;
                        if (reduction > 50) {
                            reductionValue.style.backgroundColor = '#4caf50';
                        } else if (reduction > 20) {
                            reductionValue.style.backgroundColor = '#ff9800';
                        } else {
                            reductionValue.style.backgroundColor = '#f44336';
                        }
                        
                        estimationNote.innerHTML = '<i class="bi bi-check-circle"></i> Estimation ready';
                    } else {
                        estimationNote.innerHTML = '<i class="bi bi-exclamation-triangle"></i> Estimation failed';
                    }
                })
                .catch(error => {
                    console.error('Estimation error:', error);
                    estimationNote.innerHTML = '<i class="bi bi-exclamation-triangle"></i> Estimation failed';
                });
            }

            function debouncedEstimation() {
                clearTimeout(estimationTimeout);
                estimationTimeout = setTimeout(updateSizeEstimation, 300);
            }

            // Mode switching
            modeBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const mode = this.dataset.mode;
                    currentMode = mode;
                    
                    // Update UI
                    modeBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    
                    if (mode === 'quality') {
                        qualitySettings.style.display = 'block';
                        sizeSettings.style.display = 'none';
                    } else {
                        qualitySettings.style.display = 'none';
                        sizeSettings.style.display = 'block';
                    }
                    
                    if (currentFile) {
                        debouncedEstimation();
                    }
                });
            });

            // Event listeners
            qualitySlider.addEventListener('input', function() {
                qualityValue.textContent = this.value + '%';
                if (currentFile && currentMode === 'quality') {
                    debouncedEstimation();
                }
            });

            targetSizeInput.addEventListener('input', function() {
                if (currentFile && currentMode === 'size') {
                    debouncedEstimation();
                }
            });

            // File handling
            dropZone.addEventListener('click', () => {
                if (!isProcessing) fileInput.click();
            });
            
            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.classList.add('dragover');
            });
            
            dropZone.addEventListener('dragleave', () => {
                dropZone.classList.remove('dragover');
            });
            
            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.classList.remove('dragover');
                
                if (e.dataTransfer.files.length && !isProcessing) {
                    handleFile(e.dataTransfer.files[0]);
                }
            });

            fileInput.addEventListener('change', (e) => {
                if (e.target.files.length && !isProcessing) {
                    // Reset previous file and handle new one
                    resetFileState();
                    handleFile(e.target.files[0]);
                }
            });

            function resetFileState() {
                currentFile = null;
                originalFileSize = 0;
                fileInfo.style.display = 'none';
                modeSwitch.style.display = 'none';
                sizePreview.style.display = 'none';
                compressBtn.disabled = true;
            }

            function handleFile(file) {
                const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/bmp', 'image/webp'];
                if (!validTypes.includes(file.type) && !file.name.match(/\.(jpg|jpeg|png|gif|bmp|webp)$/i)) {
                    alert('Please select a valid image file (JPG, PNG, GIF, BMP, WEBP).');
                    return;
                }

                if (file.size > 40 * 1024 * 1024) {
                    alert('File size must be less than 40MB.');
                    return;
                }

                currentFile = file;
                originalFileSize = file.size;

                fileName.textContent = file.name;
                fileSize.textContent = formatBytes(file.size);
                fileInfo.style.display = 'block';
                modeSwitch.style.display = 'flex';
                sizePreview.style.display = 'block';
                compressBtn.disabled = false;

                // Set initial values
                const initialTargetSize = Math.max(10, Math.min(1024, Math.round(file.size / 1024 * 0.3)));
                targetSizeInput.value = initialTargetSize;

                // Show initial estimation
                originalSizeValue.textContent = formatBytes(file.size);
                estimatedSizeValue.textContent = 'Calculating...';
                reductionValue.textContent = '0% Reduction';
                reductionValue.style.backgroundColor = '#f44336';
                estimationNote.innerHTML = '<i class="bi bi-hourglass-split"></i> Calculating estimation...';

                // Get initial estimation
                debouncedEstimation();

                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fileInput.files = dataTransfer.files;
            }

            // Fix: Detect download completion properly
            form.addEventListener('submit', function(e) {
                if (isProcessing) {
                    e.preventDefault();
                    return;
                }

                isProcessing = true;
                downloadStarted = false;
                compressBtn.classList.add('btn-processing');
                buttonText.textContent = 'Processing...';
                compressBtn.disabled = true;

                // Set timeout to auto-reset if download doesn't start
                const processingTimeout = setTimeout(() => {
                    if (!downloadStarted) {
                        resetButton();
                        alert('Processing took too long. Please try again.');
                    }
                }, 30000);

                // Listen for download start
                const originalSubmit = form.submit.bind(form);
                
                // Create iframe to detect download
                const iframe = document.createElement('iframe');
                iframe.style.display = 'none';
                iframe.name = 'download-frame';
                document.body.appendChild(iframe);
                
                form.target = 'download-frame';
                
                iframe.onload = function() {
                    clearTimeout(processingTimeout);
                    setTimeout(resetButton, 1000);
                };
            });

            function resetButton() {
                isProcessing = false;
                downloadStarted = false;
                compressBtn.classList.remove('btn-processing');
                buttonText.textContent = 'Compress and Download';
                compressBtn.disabled = false;
                
                // Remove any existing iframe
                const existingIframe = document.querySelector('iframe[name="download-frame"]');
                if (existingIframe) {
                    existingIframe.remove();
                }
            }

            // Reset when page becomes visible again (download completed)
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden && isProcessing) {
                    // Page became visible again, download likely completed
                    setTimeout(resetButton, 1000);
                }
            });

            // Also reset on focus
            window.addEventListener('focus', function() {
                if (isProcessing) {
                    setTimeout(resetButton, 1000);
                }
            });
        });
    </script>
@stop