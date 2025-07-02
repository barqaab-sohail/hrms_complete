@extends('layouts.master.master')
@section('title', 'Shorter URL')
@section('Heading')
<h3 class="text-themecolor">Shorter URL</h3>
@stop
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">URL Shortener</div>
                <div class="card-body">
                    <form id="shortenForm">
                        @csrf
                        <div class="mb-3">
                            <label for="url" class="form-label">Enter URL to shorten:</label>
                            <input type="url" class="form-control" id="url" name="url" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Shorten</button>
                    </form>
                    <div id="result" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#shortenForm').submit(function(e) {
            e.preventDefault();
            
            $.ajax({
                url: "{{ route('shorten.store') }}",
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#result').html(`
                        <div class="alert alert-success">
                            <p>Short URL: <a href="${response.short_url}" target="_blank">${response.short_url}</a></p>
                        </div>
                    `);
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    let errorHtml = '<div class="alert alert-danger"><ul>';
                    
                    for (let error in errors) {
                        errorHtml += `<li>${errors[error]}</li>`;
                    }
                    
                    errorHtml += '</ul></div>';
                    $('#result').html(errorHtml);
                }
            });
        });
    });
</script>

@stop