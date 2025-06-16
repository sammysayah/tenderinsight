@extends('adminlte::page')

@section('title', 'Company Documents')

@section('content_header')
    <h1>Company Documents</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3 class="card-title">Document List</h3>
          
        </div>
    </div>

    <div class="card-body table-responsive p-0">
        <table id="documentTable" class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Document Name</th>
                    <th>Document Type</th>
                    <th>Document Title</th>
                    <th>Year</th>
                    <th>Expiry Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="document-list">
                @foreach($documents as $document)
                <tr>
                    <td>{{ $document->id }}</td>
                    <td>{{ $document->document_name }}</td>
                    <td>{{ $document->document_type }}</td>
                    <td>{{ $document->document_title ?? 'N/A' }}</td>
                    <td>{{ $document->year }}</td>
                    <td>{{ $document->expiry_date }}</td>
                    <td>
                        {{-- Download Button --}}
                        <a href="{{ route('admin.csmldoc.download', $document->id) }}" 
                           class="btn btn-sm btn-success download-document" 
                           data-id="{{ $document->id }}"
                           data-title="{{ $document->document_title }}">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{-- Pagination --}}
        <div class="d-flex justify-content-center">
            {{ $documents->links() }}
        </div>
    </div>
</div>
@stop
@section('footer')
    <div class="float-right">
        Version: {{ config('app.version', '1.0.0') }}
    </div>
    <strong>
        Developed By: <a href="{{ config('app.company_url', 'mailto:sayahsamson@gmail.com') }}">
            {{ config('app.company_name', 'Samson Saya') }}
        </a>
    </strong>
@stop

@section('css')
<style>
.custom-loader {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1050;
    background-color: rgba(255, 255, 255, 0.8);
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
}

.spinner-border {
    width: 3rem;
    height: 3rem;
}
</style>
<link rel="stylesheet" href="/css/admin_custom.css">
<!-- Include DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize DataTable
    $('#documentTable').DataTable({
        search: {
            caseInsensitive: true
        },
        paging: true,
        ordering: true,
        info: true,
    });

    const documentList = document.getElementById('document-list');

    // Event listener for download buttons
    documentList.addEventListener('click', function (e) {
        if (e.target.classList.contains('download-document') || e.target.closest('.download-document')) {
            e.preventDefault();

            const button = e.target.closest('.download-document');
            const documentId = button.getAttribute('data-id');
            const documentTitle = button.getAttribute('data-title');
            const url = button.getAttribute('href'); // Get the URL directly from the href attribute

            // Show custom loader
            const loader = document.createElement('div');
            loader.className = 'custom-loader';
            loader.innerHTML = `
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Downloading...</span>
                </div>`;
            document.body.appendChild(loader);

            // Trigger the download
            fetch(url, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}', // Ensure CSRF token is included
                },
            })
            .then(response => {
                console.log('Response:', response); // Debug response
                if (response.ok) {
                    return response.blob().then(blob => {
                        const downloadUrl = window.URL.createObjectURL(blob);
                        const link = document.createElement('a');
                        link.href = downloadUrl;
                        link.download = documentTitle; // Use the document title here as the filename
                        link.click();
                        window.URL.revokeObjectURL(downloadUrl);
                    });
                } else {
                    // Handle non-OK response
                    return response.json().then(error => {
                        console.error('Error response:', error);
                        alert(error.error || 'Failed to download the file.');
                    });
                }
            })
            .catch(err => {
                console.error('Fetch error:', err);
                alert('An error occurred during the download.');
            })
            .finally(() => {
                // Remove the loader
                loader.remove();
            });
        }
    });
});
</script>
@stop
