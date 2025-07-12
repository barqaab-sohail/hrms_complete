@extends('layouts.master.master')
@section('title', 'Background Remover')
@section('Heading')
<h3 class="text-themecolor">Background Remover</h3>
@stop
@section('content')
<style>
    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
    .preview { max-width: 100%; margin-top: 20px; display: none; }
    #progress-container { display: none; margin: 20px 0; }
    #progress-bar { width: 100%; background-color: #f0f0f0; border-radius: 5px; }
    #progress-bar-fill { height: 20px; background-color: #4CAF50; border-radius: 5px; width: 0%; }
</style>
<div class="card">
    <div class="card-body">
        <h1>Background Remover</h1>
        @if(session('error'))
            <div style="color: red;">{{ session('error') }}</div>
        @endif
        
        <form id="bg-remove-form" method="POST" action="{{ route('bg.remove') }}" enctype="multipart/form-data">
            @csrf
            <input type="file" name="image" id="image-upload" required accept="image/*">
            <button type="submit" id="process-btn">Remove Background</button>
        </form>
        
        <div id="progress-container">
            <p>Processing image...</p>
            <div id="progress-bar">
                <div id="progress-bar-fill"></div>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('bg-remove-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = e.target;
        const formData = new FormData(form);
        const progressBar = document.getElementById('progress-bar-fill');
        const progressContainer = document.getElementById('progress-container');
        
        // Show progress bar
        progressContainer.style.display = 'block';
        document.getElementById('process-btn').disabled = true;
        
        // Create AJAX request
        const xhr = new XMLHttpRequest();
        
        // Progress event
        xhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
                const percentComplete = (e.loaded / e.total) * 50; // First 50% for upload
                progressBar.style.width = percentComplete + '%';
            }
        });
        
        // Load event (for download progress)
        xhr.addEventListener('load', function(e) {
            if (xhr.status === 200) {
                // Create download link
                const blob = new Blob([xhr.response], {type: 'image/png'});
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'background_removed.png';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            } else {
                alert('Error processing image');
            }
            
            // Reset form
            progressContainer.style.display = 'none';
            document.getElementById('process-btn').disabled = false;
        });
        
        // Open and send request
        xhr.open('POST', form.action, true);
        xhr.responseType = 'blob';
        xhr.send(formData);
        
        // Simulate processing progress (since we can't get actual Python progress)
        let progress = 50;
        const interval = setInterval(() => {
            progress += Math.random() * 5;
            if (progress >= 95) {
                clearInterval(interval);
            }
            progressBar.style.width = progress + '%';
        }, 500);
    });
</script>

@stop
