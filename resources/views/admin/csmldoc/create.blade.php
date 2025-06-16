@extends('adminlte::page')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Upload New Document</h3>
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

            <form id="uploadForm" action="{{ route('admin.csmldoc.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="document_name">Document Name</label>
                    <input type="text" class="form-control" name="document_name" value="{{ old('document_name') }}" required>
                </div>
                <div class="form-group">
                    <label for="document_type">Document Type</label>
                    <select class="form-control" name="document_type" required>
                        <option value="">Select Type</option>
                        <option value="SMSE certificate">SMSE Certificate</option>
                        <option value="Company registration">Company Registration</option>
                        <option value="PPDA certificate">PPDA Certificate</option>
                        <option value="Tax clearance certificate">Tax Clearance Certificate</option>
                        <option value="Withholding certificate">Withholding Certificate</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="year">Year</label>
                    <input type="number" class="form-control" name="year" value="{{ old('year') }}" required>
                </div>
                <div class="form-group">
                    <label for="expiry_date">Expiry Date</label>
                    <input type="date" class="form-control" name="expiry_date" value="{{ old('expiry_date') }}" required>
                </div>
                <div class="form-group">
                    <label for="file">Upload File</label>
                    <input type="file" class="form-control" name="file" required>
                </div>
                <button type="submit" class="btn btn-primary">Upload Document</button>
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