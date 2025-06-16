@extends('adminlte::page')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Edit Document</h3>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('csmldoc.update', $document->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="document_name">Document Name</label>
                    <input type="text" class="form-control" name="document_name" value="{{ $document->document_name }}" required>
                </div>
                <div class="form-group">
                    <label for="document_type">Document Type</label>
                    <select class="form-control" name="document_type" required>
                        <option value="SMSE certificate" {{ $document->document_type == 'SMSE certificate' ? 'selected' : '' }}>SMSE Certificate</option>
                        <option value="Company registration" {{ $document->document_type == 'Company registration' ? 'selected' : '' }}>Company Registration</option>
                        <option value="PPDA certificate" {{ $document->document_type == 'PPDA certificate' ? 'selected' : '' }}>PPDA Certificate</option>
                        <option value="Tax clearance certificate" {{ $document->document_type == 'Tax clearance certificate' ? 'selected' : '' }}>Tax Clearance Certificate</option>
                        <option value="Withholding certificate" {{ $document->document_type == 'Withholding certificate' ? 'selected' : '' }}>Withholding Certificate</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="year">Year</label>
                    <input type="number" class="form-control" name="year" value="{{ $document->year }}" required>
                </div>
                <div class="form-group">
                    <label for="expiry_date">Expiry Date</label>
                    <input type="date" class="form-control" name="expiry_date" value="{{ $document->expiry_date }}" required>
                </div>
                <div class="form-group">
                    <label for="file">Upload New File (optional)</label>
                    <input type="file" class="form-control" name="file">
                </div>
                <button type="submit" class="btn btn-primary">Update Document</button>
            </form>
        </div>
    </div>
@endsection
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