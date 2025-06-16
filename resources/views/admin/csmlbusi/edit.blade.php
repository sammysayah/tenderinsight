@extends('adminlte::page')

@section('title', 'Edit Business Document')

@section('content_header')
    <h1>Edit Business Document: {{ $csmlbusi->client_name }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- ✅ FORM 1: Update Business Document Details -->
            <form action="{{ route('admin.csmlbusi.update', $csmlbusi->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="client_name">Client Name</label>
                    <input type="text" name="client_name" id="client_name" class="form-control" 
                        value="{{ old('client_name', $csmlbusi->client_name) }}">
                </div>

                <div class="form-group">
                    <label for="business_type">Business Type</label>
                    <select name="business_type" id="business_type" class="form-control">
                        <option value="tender" {{ $csmlbusi->business_type == 'tender' ? 'selected' : '' }}>Tender</option>
                        <option value="quotation" {{ $csmlbusi->business_type == 'quotation' ? 'selected' : '' }}>Quotation</option>
                        <option value="prequalification" {{ $csmlbusi->business_type == 'prequalification' ? 'selected' : '' }}>Prequalification</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="year">Year</label>
                    <input type="number" name="year" id="year" class="form-control" 
                        value="{{ old('year', $csmlbusi->year) }}">
                </div>

                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="number" name="amount" id="amount" class="form-control" 
                        value="{{ old('amount', $csmlbusi->amount) }}">
                </div>

                <div class="form-group">
                    <label for="expiry_date">Expiry Date</label>
                    <input type="date" name="expiry_date" id="expiry_date" class="form-control" 
                        value="{{ old('expiry_date', $csmlbusi->expiry_date ? \Carbon\Carbon::parse($csmlbusi->expiry_date)->format('Y-m-d') : '') }}">
                </div>

                <div class="form-group">
                    <label for="bid_status">Bid Status</label>
                    <select name="bid_status" id="bid_status" class="form-control">
                        <option value="Progress" {{ $csmlbusi->bid_status == 'Progress' ? 'selected' : '' }}>Progress</option>
                        <option value="won" {{ $csmlbusi->bid_status == 'won' ? 'selected' : '' }}>Won</option>
                        <option value="lost" {{ $csmlbusi->bid_status == 'lost' ? 'selected' : '' }}>Lost</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="files">Upload New Files</label>
                    <input type="file" name="files[]" id="files" class="form-control" multiple>
                </div>

                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>

            <hr>

            <!-- ✅ FORM 2: Delete Files -->
            <div class="form-group">
                <label>Existing Files</label>
                <ul>
                    @if(isset($csmlbusi->documents) && $csmlbusi->documents->isNotEmpty())
                        @foreach($csmlbusi->documents as $document)
                            <li>
                                <a href="{{ Storage::url($document->file_path) }}" target="_blank">
                                    {{ $document->document_name ?? basename($document->document_title) }}
                                </a>

                                <!-- Delete Form -->
                                <form action="{{ route('admin.csmlbusi.deleteDocument', $document->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </li>
                        @endforeach
                    @else
                        <li>No files available.</li>
                    @endif
                </ul>
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