@extends('adminlte::page')

@section('title', 'Company Documents')

@section('content_header')
    <h1>CSML Business Documents</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3 class="card-title">Document List</h3>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <!-- DataTable Section -->
        <table id="document-table" class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Client Name</th>
                    <th>Business Type</th>
                    <th>Document Title</th>
                    <th>Status</th>
                    <th>Year</th>
                    <th>Expiry Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="document-list">
                @foreach($businesses as $business)
                    <tr>
                        <td>{{ $business->id }}</td>
                        <td>{{ $business->client_name }}</td>
                        <td>{{ $business->business_type }}</td>
                        <td>
                            {{-- Display document count --}}
                            {{ $business->documents->count() }} documents
                        </td>
                        <td>{{ $business->bid_status }}</td>
                        <td>{{ $business->year }}</td>
                        <td>{{ $business->expiry_date }}</td>
                        <td>
                            {{-- Download All Button --}}
                            <a href="{{ route('admin.csmlbusi.download', $business->id) }}" 
                               class="btn btn-sm btn-success download-business-document" 
                               data-id="{{ $business->id }}">
                                <i class="fas fa-download"></i> Download Business Documents
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
            {{ $businesses->links() }} {{-- Updated to businesses --}}
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
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
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
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize DataTable
    $('#document-table').DataTable({
        responsive: true, // Enable responsive design for the table
        autoWidth: false, // Allow automatic adjustment of column widths
        order: [[ 0, 'desc' ]] // Optionally, order by the first column (ID)
    });

    const documentList = document.getElementById('document-list');

    // Event listener for download buttons
    documentList.addEventListener('click', function (e) {
        if (e.target.classList.contains('download-business-document') || e.target.closest('.download-business-document')) {
            e.preventDefault();

            const button = e.target.closest('.download-business-document');
            const businessId = button.getAttribute('data-id');
            const clientName = button.closest('tr').querySelector('td:nth-child(2)').textContent.trim(); // Extract client name from the second column
            const url = button.getAttribute('href'); // Get the URL directly from the href attribute

            // Show custom loader
            const loader = document.createElement('div');
            loader.className = 'custom-loader';
            loader.innerHTML = `
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Downloading...</span>
                </div>`;
            document.body.appendChild(loader);

            // Trigger the download using fetch
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
                        link.download = `${clientName}_business_documents.zip`; // Use the client name in the file name
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
