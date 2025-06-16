@extends('adminlte::page')

@section('title', 'Company Documents')

@section('content_header')
    <h1>Company Documents</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3 class="card-title">Business Document List</h3>
            {{-- Search bar --}}
            <div class="input-group w-50">
                <input type="text" id="search-documents" class="form-control" placeholder="Search documents...">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Client  Name</th>
                    <th>Business Type</th>
                    <th>Year</th>
                    <th>Amount</th>
                    <th>Expiry Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="document-list">
                @foreach($documents as $document)
                <tr>
                    <td>{{ $document->id }}</td>
                    <td>{{ $document->client_name }}</td>
                    <td>{{ $document->business_type }}</td>
                    <td>{{ $document->year }}</td>
                    <td>{{ $document->amount }}</td>
                    <td>{{ $document->expiry_date }}</td>
                    <td>
                        {{-- Edit Button --}}
                        <a href="{{ route('admin.csmlbusi.edit', $document->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        
                        {{-- Delete Button --}}
                        <button class="btn btn-sm btn-danger delete-document" data-id="{{ $document->id }}">
                            <i class="fas fa-trash"></i> Delete
                        </button>
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
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Search functionality
    const searchInput = document.getElementById('search-documents');
    const documentList = document.getElementById('document-list');

    searchInput.addEventListener('keyup', function () {
        const query = searchInput.value;

        // If the search input is empty, we could reset the list or show all documents
        if (query.length === 0) {
            documentList.innerHTML = ''; // Optional: Clear the list or show all items
            return;
        }

        fetch(`/admin/csmlbusi?search=${query}`)
            .then(response => response.json())
            .then(data => {
                let rows = '';

                if (data.data.length > 0) {
                    data.data.forEach(document => {
                        rows += `
                            <tr>
                                <td>${document.id}</td>
                                <td>${document.client_name}</td>
                                <td>${document.business_type}</td>
                                <td>${document.year}</td>
                                <td>${document.expiry_date}</td>
                                <td>
                                    <a href="/admin/csmlbusi/${document.id}/edit" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <button class="btn btn-sm btn-danger delete-document" data-id="${document.id}">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    rows = '<tr><td colspan="6" class="text-center">No documents found.</td></tr>';
                }

                documentList.innerHTML = rows;
            });
    });

    // Delete functionality
    documentList.addEventListener('click', function (e) {
        if (e.target.classList.contains('delete-document')) {
            const documentId = e.target.getAttribute('data-id');

            if (confirm('Are you sure you want to delete this document?')) {
                fetch(`/admin/csmlbusi/${documentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.success);
                    location.reload(); // Reload the page after deletion
                });
            }
        }
    });
});
</script>
@stop
